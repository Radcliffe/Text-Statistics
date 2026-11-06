# Backlog

Known issues, grouped by area, with the most important first in each group. Each
one was reproduced on 2026-11-06, except where noted: issues 1-11 against `330786a`,
and the security issues against `96babe3`. Examples use PHP double-quoted string
escapes.

Most fixes here change scores for non-ASCII text, so keep each in its own commit
and mention the change in its commit message.

## Scoring

### 1. Syllable counts ignore non-ASCII letters

`syllableCount()` removes everything except `A-Za-z` and only treats `aeiouy` as
vowels (`Syllables.php:269`, `Syllables.php:297`). This skews the syllable-based
scores, such as Flesch-Kincaid.

- `café` is treated as `caf` and scores 1 syllable instead of 2.
- A word in another script scores 1 however long it is, as `библиотека` does.

**Suggested fix:** strip accents to their base letters before counting, which
fixes Latin-script text. Other scripts would need their own rules, so decide
whether to support them or document them as unsupported.

### 2. Punctuation from HTML entities is not normalised

`cleanText()` replaces curly quotes, dashes and ellipses before it calls
`html_entity_decode()`, so the entity forms are never replaced.

- `a&mdash;b` cleans to `a—b.` and counts as 1 word, where `a--b` counts as 2.
- `wait&hellip; now` counts as 1 sentence, where `wait... now` counts as 2.
- `it&rsquo;s here` keeps its curly apostrophe.

**Suggested fix:** decode entities before the punctuation replacements. Check the
order against tag stripping, because decoding `&lt;` first would turn escaped text
into tags that then get stripped.

### 3. Dale-Chall and Spache mishandle non-ASCII letters and symbols

Both remove characters with `[^A-za-z' ]` (`TextStatistics.php:432`,
`TextStatistics.php:459`).

- Accented letters are dropped, so `caré` is looked up as `car` and counts as easy.
- `A-z` is a typo for `A-Za-z`, so `_` and `^` survive. `the_` counts as difficult,
  while `the` does not.

**Suggested fix:** correct the range. Then either look words up with accents
converted to base letters (`caré` as `care`), or count accented words as difficult.

## Encoding

### 4. `$strEncoding` conflicts with `cleanText()` always returning UTF-8

Cleaned text is always UTF-8, but `textLength()`, `lowerCase()`, `upperCase()` and
`substring()` still apply the caller's encoding to it.

- `Text::characterCount("café.", 'ISO-8859-1')` returns 6 instead of 5.
- `Text::lowerCase("CAFÉ", 'ISO-8859-1')` leaves the `É` bytes garbled.
- Not reproduced: the proper noun check in `wordsWithThreeSyllables()` uses
  `substring()` and `upperCase()` the same way, so it is probably affected.

**Suggested fix:** decide what `$strEncoding` means. It could describe the input
and drive the conversion in `cleanText()`, or be deprecated, with everything after
cleaning using UTF-8.

### 5. Windows-1252 bytes without a replacement become control characters

`cleanText()` converts single-byte text from ISO-8859-1 (`Text.php:63`), but also
replaces some Windows-1252 punctuation first. Other bytes in `0x80`-`0x9F` become
C1 control characters, so `\x80` (`€`) becomes `U+0080`.

**Suggested fix:** convert from Windows-1252 instead, which could also replace the
`chr()` list if the punctuation is then handled by the UTF-8 replacements.

### 6. Text mixing Latin-1 and UTF-8 is still double-encoded

Any invalid byte makes the whole string count as single-byte, so its UTF-8 parts
get encoded twice. `"caf\xE9 it\xE2\x80\x99s"` cleans to `café itâ..s.` with control
characters in place of the apostrophe.

**Suggested fix:** probably leave as is unless real input needs it, since repairing
this means converting segment by segment.

## Word splitting

### 7. Other Unicode spaces do not separate words

Only ASCII whitespace and U+00A0 are handled. Thin space (U+2009), em space
(U+2003), narrow non-breaking space (U+202F) and ideographic space (U+3000) each
leave `same<space>cat` as 1 word.

**Suggested fix:** replace `\p{Z}` in `cleanText()` and extend
`Text::WORD_SEPARATOR` to match.

### 8. Direct `Text::wordCount()` calls count leading and trailing spaces

`wordCount()` counts spaces without trimming, so `'because special '` is 3 words.
`Syllables::averageSyllablesPerWord()` then returns 1.33 instead of 2. The public
`TextStatistics` API is not affected, because `cleanText()` trims first.

**Suggested fix:** trim in `wordCount()` before counting, and skip empty strings in
the `Syllables` loops.

### 9. Direct calls do not treat a lone `\xA0` byte as a separator

`Text::wordCount("same\xA0cat")` returns 1. `Text::WORD_SEPARATOR` cannot match a
lone `A0`, because that byte also ends a UTF-8 `à`. The public API handles this
through `cleanText()`.

**Suggested fix:** probably none beyond documenting that direct calls expect UTF-8.

## Letters

### 10. Combining vowel signs are not counted as letters

`letterCount()` counts `\p{L}` only, so scripts that write vowels as combining
marks come up short. `हिन्दी` has 6 code points and counts as 3 letters.

**Suggested fix:** count each base letter with its marks only for scripts that need
it. Counting all marks would break `e` + combining accent, which must stay 1 letter.

## Tests

### 11. CMU word list test is marked incomplete

`TextStatisticsCMULexTest.php:4415` stops at `markTestIncomplete()`. Its note says
the words after it may fail because of either the code or the test, so they are not
checked. This is the 1 incomplete test in the suite.

**Suggested fix:** measure how many of those words fail, then fix the syllable rules
or correct the expected values.

## Security

### 12. Text is shared between instances (low to medium)

`TextStatistics::$strText` is static (`TextStatistics.php:67`), so a new instance
called without text uses the last text from any other instance. In a long-running
worker, this can expose one user's text to another.

After `$a->wordCount('confidential tenant A text')`, a new instance `$b` gives:

- `$b->wordCount()` returns 4.
- `$b->setText(false)` returns `confidential tenant A text.`

**Suggested fix:** make it an instance property. This is a breaking change for any
code relying on the text being shared between instances.

### 13. Large inputs have no size limit (low to medium)

Scoring takes time and memory in proportion to input size, and the library sets no
limit. `totalSyllables()` on 9 MB of unique words took about 20s and peaked at
107 MB, and text beyond that can still hit a 128 MB memory limit with a fatal error.

**Suggested fix:** applications taking untrusted text should cap its size before
calling the library. Document this in the README, and consider an optional limit.

### 14. Regexes take quadratic time without PCRE JIT (low)

`([\.\s]*\.[\.\s]*)` in `cleanText()` slows down on long runs of whitespace when
`pcre.jit` is off. 40 KB of spaces took 5.8s, and doubling the input takes about four
times as long. With JIT on, 16 MB took 0.13s, so this mainly affects servers without
JIT.

**Suggested fix:** collapse whitespace before that step, and benchmark with
`pcre.jit=0`.

### 15. CI downloads and runs tools without verifying them (low)

`.travis.yml` fetches `composer.phar` over plain HTTP and runs it, and runs
`ocular.phar` from HTTPS, both with no checksum. Not reproduced: this comes from
reading the file.

**Suggested fix:** use HTTPS with checksums, or delete the file if Travis is no
longer used.
