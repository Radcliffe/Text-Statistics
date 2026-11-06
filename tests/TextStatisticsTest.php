<?php

class TextStatisticsTest extends \PHPUnit\Framework\TestCase
{

    /*

        This file contains the more basic tests - short sentences, word counts,
        sentence counts, and so on. Longer texts are split into their own test
        files for convenience.

    */

    protected $TextStatistics = null;

    public function setUp(): void
    {
        $this->TextStatistics = new DaveChild\TextStatistics\TextStatistics();
        $this->TextStatistics->normalise = false;
    }

    public function tearDown(): void
    {
        unset($this->objTextStatistics);
    }

    /* Test Cleaning of text
    -------------------- */
    public function testCleaning()
    {
        $this->assertSame('', DaveChild\TextStatistics\Text::cleanText(false));
        $this->assertSame('There once was a little sausage named Baldrick. and he lived happily ever after.', DaveChild\TextStatistics\Text::cleanText('There once was a little sausage named Baldrick. . . .  And he lived happily ever after.!! !??'));
    }

    /* Test Case changes
    -------------------- */
    public function testCases()
    {
        $this->assertSame('banana', DaveChild\TextStatistics\Text::lowerCase('banana'));
        $this->assertSame('banana', DaveChild\TextStatistics\Text::lowerCase('Banana'));
        $this->assertSame('banana', DaveChild\TextStatistics\Text::lowerCase('BanAna'));
        $this->assertSame('banana', DaveChild\TextStatistics\Text::lowerCase('BANANA'));
        $this->assertSame('BANANA', DaveChild\TextStatistics\Text::upperCase('banana'));
        $this->assertSame('BANANA', DaveChild\TextStatistics\Text::upperCase('Banana'));
        $this->assertSame('BANANA', DaveChild\TextStatistics\Text::upperCase('BanAna'));
        $this->assertSame('BANANA', DaveChild\TextStatistics\Text::upperCase('BANANA'));
    }

    /* Test Counts
    -------------------- */
    public function testCounts()
    {
        $this->assertSame(47, DaveChild\TextStatistics\Text::characterCount('There once was a little sausage named Baldrick.'));
        $this->assertSame(47, DaveChild\TextStatistics\Text::textLength('There once was a little sausage named Baldrick.'));
        $this->assertSame(39, DaveChild\TextStatistics\Text::letterCount('There once was a little sausage named Baldrick.'));
        $this->assertSame(0, DaveChild\TextStatistics\Text::letterCount(''));
        $this->assertSame(0, DaveChild\TextStatistics\Text::letterCount(' '));
        $this->assertSame(0, DaveChild\TextStatistics\Text::wordCount(''));
        $this->assertSame(0, DaveChild\TextStatistics\Text::wordCount(' '));
        $this->assertSame(0, DaveChild\TextStatistics\Text::sentenceCount(''));
        $this->assertSame(0, DaveChild\TextStatistics\Text::sentenceCount(' '));
        $this->assertSame(1, $this->TextStatistics->letterCount('a'));
        // Reset text before running second letter count check or it will use preset text of "a"
        $this->TextStatistics->setText('');
        $this->assertSame(0, $this->TextStatistics->letterCount(''));
        $this->assertSame(46, $this->TextStatistics->letterCount('this sentence has 30 characters, not including the digits'));
    }

    /* Test Syllables
    -------------------- */
    public function testSyllableCountBasicWords()
    { // "Normal" words
        $this->assertEquals(0, $this->TextStatistics->syllableCount('.'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('a'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('was'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('the'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('and'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('foobar'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('hello'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('world'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('wonderful'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('simple'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('easy'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('hard'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('quick'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('brown'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('fox'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('jumped'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('over'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('lazy'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('dog'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('camera'));
    }

    public function testSyllableCountComplexWords()
    { // Odd syllables, long words, difficult sounds
        $this->assertEquals(12, $this->TextStatistics->syllableCount('antidisestablishmentarianism'));
        $this->assertEquals(14, $this->TextStatistics->syllableCount('supercalifragilisticexpialidocious'));
        $this->assertEquals(8, $this->TextStatistics->syllableCount('chlorofluorocarbonation'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('forethoughtfulness'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('phosphorescent'));
        $this->assertEquals(5, $this->TextStatistics->syllableCount('theoretician'));
        $this->assertEquals(5, $this->TextStatistics->syllableCount('promiscuity'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('unbutlering'));
        $this->assertEquals(5, $this->TextStatistics->syllableCount('continuity'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('craunched'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('squelched'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('scrounge'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('coughed'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('smile'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('monopoly'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('doughey'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('doughier'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('leguminous'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('thoroughbreds'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('special'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('delicious'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('spatial'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('pacifism'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('coagulant'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('shouldn\'t'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('mcdonald'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('audience'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('finance'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('prevalence'));
        $this->assertEquals(5, $this->TextStatistics->syllableCount('impropriety'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('alien'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('dreadnought'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('verandah'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('similar'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('similarly'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('central'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('cyst'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('term'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('order'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('fur'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('sugar'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('paper'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('make'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('gem'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('program'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('hopeless'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('hopelessly'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('careful'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('carefully'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('stuffy'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('thistle'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('teacher'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('unhappy'));
        $this->assertEquals(5, $this->TextStatistics->syllableCount('ambiguity'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('validity'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('ambiguous'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('deserve'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('blooper'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('scooped'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('deserve'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('deal'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('death'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('dearth'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('deign'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('reign'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('bedsore'));
        $this->assertEquals(5, $this->TextStatistics->syllableCount('anorexia'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('anymore'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('cored'));
        $this->assertEquals(1, $this->TextStatistics->syllableCount('sore'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('foremost'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('restore'));
        $this->assertEquals(2, $this->TextStatistics->syllableCount('minute'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('manticores'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('asparagus'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('unexplored'));
        $this->assertEquals(4, $this->TextStatistics->syllableCount('unexploded'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('CAPITALS'));
    }

    // These are fairly common words that are exceptions to given rules and that can not
    // easily be programmed for. I've added them here for documentation purposes as much
    // as anything else. If you find a way to program rules for any of these, move them
    // into the section above. Many compound words will end up here.
    public function testSyllableCountProgrammedExceptions()
    {
        $this->assertEquals(3, $this->TextStatistics->syllableCount('simile'));
        // Compounds that have caused problems so far
        // Problem: far too many compound words to list exhaustively.
        $this->assertEquals(2, $this->TextStatistics->syllableCount('shoreline'));
        $this->assertEquals(3, $this->TextStatistics->syllableCount('forever'));
    }

    public function testAverageSyllablesPerWord()
    {
        $this->assertEquals(1, $this->TextStatistics->averageSyllablesPerWord('and then there was one'));
        $this->assertEquals(2, $this->TextStatistics->averageSyllablesPerWord('because special ducklings deserve rainbows'));
        $this->assertEquals(1.5, $this->TextStatistics->averageSyllablesPerWord('and then there was one because special ducklings deserve rainbows'));
    }

    /* wordCount() collapses whitespace but these loops split on single spaces,
       so the two counts can differ in either direction.
    -------------------- */
    public function testSyllableLoopsWhenWordCountExceedsSplitWords()
    {
        // Called directly because cleanText() replaces tabs on the public API.
        $this->assertEquals(1, DaveChild\TextStatistics\Syllables::averageSyllablesPerWord("and\tthen there"));
        $this->assertEquals(1, DaveChild\TextStatistics\Syllables::wordsWithThreeSyllables("cat\tdog elephant"));
    }

    /* Tabs and other whitespace separate words. "same\tcat" scores 3 syllables
       if the words stay joined, and 2 once they are split.
    -------------------- */
    public function testCleaningReplacesTabsWithSpaces()
    {
        $this->assertSame('and then there.', DaveChild\TextStatistics\Text::cleanText("and\tthen \t there"));
        $this->assertSame('and then there.', DaveChild\TextStatistics\Text::cleanText("and\x0bthen\x0cthere"));
    }

    public function testTabsSeparateWordsInPublicApi()
    {
        $this->assertEquals(2, $this->TextStatistics->totalSyllables("same\tcat"));
        $this->assertEquals(1, $this->TextStatistics->averageSyllablesPerWord("same\tcat"));
        $this->assertEquals(0, $this->TextStatistics->wordsWithThreeSyllables("same\tcat"));
        $this->assertEquals(0, $this->TextStatistics->daleChallDifficultWordCount("and\tthen\tthere"));
        $this->assertEquals(0, $this->TextStatistics->spacheDifficultWordCount("and\tthen\tthere"));
    }

    public function testTabsSeparateWordsInSyllables()
    {
        $this->assertEquals(2, DaveChild\TextStatistics\Syllables::totalSyllables("same\tcat"));
        $this->assertEquals(1, DaveChild\TextStatistics\Syllables::averageSyllablesPerWord("same\tcat"));
        $this->assertEquals(0, DaveChild\TextStatistics\Syllables::wordsWithThreeSyllables("same\tcat"));
    }

    /* Non-breaking spaces separate words, in every form they can arrive in.
    -------------------- */
    public function testNonBreakingSpacesSeparateWords()
    {
        $arrForms = array("\xC2\xA0", "\xA0", '&nbsp;', '&#160;', '&#xA0;');
        foreach ($arrForms as $strSpace) {
            $strText = 'same' . $strSpace . 'cat';
            $this->assertSame('same cat.', DaveChild\TextStatistics\Text::cleanText($strText));
            $this->assertEquals(2, $this->TextStatistics->wordCount($strText));
            $this->assertEquals(2, $this->TextStatistics->totalSyllables($strText));
            $this->assertEquals(0, $this->TextStatistics->wordsWithThreeSyllables($strText));
        }
    }

    public function testAccentedLettersAreNotTreatedAsNonBreakingSpaces()
    {
        // "à" is C3 A0 in UTF-8 and E0 in ISO-8859-1.
        $this->assertEquals(3, $this->TextStatistics->wordCount("p\xC3\xA0s de deux"));
        $this->assertEquals(3, $this->TextStatistics->wordCount("p\xE0s de deux"));
        $this->assertEquals(3, DaveChild\TextStatistics\Text::wordCount("p\xC3\xA0s de deux"));
        $this->assertEquals(2, DaveChild\TextStatistics\Syllables::wordsWithThreeSyllables("p\xC3\xA0s de deux elephant ananas"));
    }

    public function testNonBreakingSpacesSeparateWordsInDirectCalls()
    {
        $this->assertEquals(2, DaveChild\TextStatistics\Text::wordCount("same\xC2\xA0cat"));
        $this->assertEquals(2, DaveChild\TextStatistics\Syllables::totalSyllables("same\xC2\xA0cat"));
        $this->assertEquals(1, DaveChild\TextStatistics\Syllables::averageSyllablesPerWord("same\xC2\xA0cat"));
        $this->assertEquals(0, DaveChild\TextStatistics\Syllables::wordsWithThreeSyllables("same\xC2\xA0cat"));
    }

    /* cleanText() returns UTF-8, converting only text that isn't UTF-8 already.
    -------------------- */
    public function testCleaningKeepsUtf8Intact()
    {
        foreach (array("p\xC3\xA0s", "ni\xC3\x91o", "\xC3\x85se", "\xC3\x96l", "\xC3\x97") as $strText) {
            $this->assertSame($strText . '.', DaveChild\TextStatistics\Text::cleanText($strText));
            $this->assertSame($strText . '.', DaveChild\TextStatistics\Text::cleanText($strText . '.'));
        }
    }

    public function testCleaningConvertsSingleByteText()
    {
        $this->assertSame("p\xC3\xA0s.", DaveChild\TextStatistics\Text::cleanText("p\xE0s"));
        $this->assertSame("it's here.", DaveChild\TextStatistics\Text::cleanText("it\x92s here"));
        $this->assertSame('a b.', DaveChild\TextStatistics\Text::cleanText("a\x97b"));
        $this->assertSame('wait. now.', DaveChild\TextStatistics\Text::cleanText("wait\x85 now"));
    }

    public function testCleaningReplacesUtf8Punctuation()
    {
        $this->assertSame("it's here.", DaveChild\TextStatistics\Text::cleanText("it\xE2\x80\x99s here"));
        $this->assertSame('a b.', DaveChild\TextStatistics\Text::cleanText("\xE2\x80\x9Ca\xE2\x80\x9D\xE2\x80\x94b"));
        $this->assertSame('wait. now.', DaveChild\TextStatistics\Text::cleanText("wait\xE2\x80\xA6 now"));
    }

    /* Word lists must come from the library, not the working directory or
       include path. Separate process, as the lists are cached once loaded.
    -------------------- */
    /**
     * @runInSeparateProcess
     */
    public function testWordListsIgnoreFilesInWorkingDirectory()
    {
        $strDir = sys_get_temp_dir() . '/textstatistics-' . uniqid();
        mkdir($strDir . '/resources', 0700, true);
        file_put_contents($strDir . '/resources/SpacheWordList.php', '<?php $arrSpacheWordList = array("hijacked");');
        file_put_contents($strDir . '/resources/DaleChallWordList.php', '<?php $arrDaleChallWordList = array("hijacked");');
        $strOriginalDir = getcwd();
        chdir($strDir);

        try {
            $this->assertNotContains('hijacked', DaveChild\TextStatistics\Resource::fetchSpacheWordList());
            $this->assertNotContains('hijacked', DaveChild\TextStatistics\Resource::fetchDaleChallWordList());
        } finally {
            chdir($strOriginalDir);
            unlink($strDir . '/resources/SpacheWordList.php');
            unlink($strDir . '/resources/DaleChallWordList.php');
            rmdir($strDir . '/resources');
            rmdir($strDir);
        }
    }

    /* Letters aren't limited to A-Z.
    -------------------- */
    public function testLetterCountIncludesNonAsciiLetters()
    {
        $this->assertSame(4, $this->TextStatistics->letterCount("caf\xC3\xA9"));
        $this->assertSame(4, $this->TextStatistics->letterCount("caf\xE9"));
        $this->assertSame(3, $this->TextStatistics->letterCount("\xD0\xBC\xD0\xB8\xD1\x80"));
        $this->assertSame(4, DaveChild\TextStatistics\Text::letterCount("ni\xC3\x91o!"));
    }

    public function testLetterCountIgnoresCombiningMarksDigitsAndPunctuation()
    {
        // "e" followed by a combining acute accent is one letter.
        $this->assertSame(4, $this->TextStatistics->letterCount("cafe\xCC\x81"));
        $this->assertSame(0, $this->TextStatistics->letterCount('123 !?'));
    }

    public function testLetterCountDoesNotDependOnRequestedEncoding()
    {
        // cleanText() always returns UTF-8, whatever encoding was requested.
        $objTextStatistics = new DaveChild\TextStatistics\TextStatistics('ISO-8859-1');
        $this->assertSame(4, $objTextStatistics->letterCount("caf\xC3\xA9"));
    }

    public function testWordsWithoutAsciiLettersHaveSyllables()
    {
        $this->assertEquals(1, $this->TextStatistics->syllableCount("\xC3\xA0"));
        $this->assertEquals(0, $this->TextStatistics->syllableCount('123'));
    }

    public function testSyllableLoopsWhenWordCountIsBelowSplitWords()
    {
        // Double spaces only reach Syllables directly; cleanText() removes them.
        $this->assertEquals(2, DaveChild\TextStatistics\Syllables::averageSyllablesPerWord('because  special ducklings'));
        $this->assertEquals(1, DaveChild\TextStatistics\Syllables::wordsWithThreeSyllables('cat  dog elephant'));
    }

    /* Test Words
    -------------------- */
    public function testWordCount()
    {
        $this->assertEquals(9, $this->TextStatistics->wordCount('The quick brown fox jumps over the lazy dog'));
        $this->assertEquals(9, $this->TextStatistics->wordCount('The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(9, $this->TextStatistics->wordCount('The quick brown fox jumps over the lazy dog. '));
        $this->assertEquals(9, $this->TextStatistics->wordCount(' The quick brown fox jumps over the lazy dog. '));
        $this->assertEquals(9, $this->TextStatistics->wordCount(' The  quick brown fox jumps over the lazy dog. '));
        $this->assertEquals(2, $this->TextStatistics->wordCount('Yes. No.'));
        $this->assertEquals(2, $this->TextStatistics->wordCount('Yes.No.'));
        $this->assertEquals(2, $this->TextStatistics->wordCount('Yes.No.'));
        $this->assertEquals(2, $this->TextStatistics->wordCount('Yes . No.'));
        $this->assertEquals(2, $this->TextStatistics->wordCount('Yes .No.'));
        $this->assertEquals(2, $this->TextStatistics->wordCount('Yes - No. '));
    }

    public function testCheckPercentageWordsWithThreeSyllables()
    {
        $this->assertEquals(9, number_format($this->TextStatistics->percentageWordsWithThreeSyllables('there is just one word with three syllables in this sentence')));
        $this->assertEquals(9, number_format($this->TextStatistics->percentageWordsWithThreeSyllables('there is just one word with three syllables in this sentence', true)));
        $this->assertEquals(0, number_format($this->TextStatistics->percentageWordsWithThreeSyllables('there are no valid words with three Syllables in this sentence', false)));
        $this->assertEquals(5, number_format($this->TextStatistics->percentageWordsWithThreeSyllables('there is one and only one word with three or more syllables in this long boring sentence of twenty words')));
        $this->assertEquals(10, number_format($this->TextStatistics->percentageWordsWithThreeSyllables('there are two and only two words with three or more syllables in this long sentence of exactly twenty words')));
        $this->assertEquals(5, number_format($this->TextStatistics->percentageWordsWithThreeSyllables('there is Actually only one valid word with three or more syllables in this long sentence of Exactly twenty words', false)));
        $this->assertEquals(0, number_format($this->TextStatistics->percentageWordsWithThreeSyllables('no long words in this sentence')));
        $this->assertEquals(0, number_format($this->TextStatistics->percentageWordsWithThreeSyllables('no long valid words in this sentence because the test ignores proper case words like this Behemoth', false)));
    }

    /* Test Sentences
    -------------------- */
    public function testSentenceCount()
    {
        $this->assertEquals(1, $this->TextStatistics->sentenceCount('This is a sentence'));
        $this->assertEquals(1, $this->TextStatistics->sentenceCount('This is a sentence.'));
        $this->assertEquals(1, $this->TextStatistics->sentenceCount('This is a sentence!'));
        $this->assertEquals(1, $this->TextStatistics->sentenceCount('This is a sentence?'));
        $this->assertEquals(1, $this->TextStatistics->sentenceCount('This is a sentence..'));
        $this->assertEquals(2, $this->TextStatistics->sentenceCount('This is a sentence. So is this.'));
        $this->assertEquals(2, $this->TextStatistics->sentenceCount("This is a sentence. \n\n So is this, but this is multi-line!"));
        $this->assertEquals(2, $this->TextStatistics->sentenceCount('This is a sentence,. So is this.'));
        $this->assertEquals(2, $this->TextStatistics->sentenceCount('This is a sentence!? So is this.'));
        $this->assertEquals(3, $this->TextStatistics->sentenceCount('This is a sentence. So is this. And this one as well.'));
        $this->assertEquals(1, $this->TextStatistics->sentenceCount('This is a sentence - but just one.'));
        $this->assertEquals(1, $this->TextStatistics->sentenceCount('This is a sentence (but just one).'));
    }

    public function testAverageWordsPerSentence()
    {
        $this->assertEquals(4, $this->TextStatistics->averageWordsPerSentence('This is a sentence'));
        $this->assertEquals(4, $this->TextStatistics->averageWordsPerSentence('This is a sentence.'));
        $this->assertEquals(4, $this->TextStatistics->averageWordsPerSentence('This is a sentence. '));
        $this->assertEquals(4, $this->TextStatistics->averageWordsPerSentence('This is a sentence. This is a sentence'));
        $this->assertEquals(4, $this->TextStatistics->averageWordsPerSentence('This is a sentence. This is a sentence.'));
        $this->assertEquals(4, $this->TextStatistics->averageWordsPerSentence('This, is - a sentence . This is a sentence. '));
        $this->assertEquals(5.5, $this->TextStatistics->averageWordsPerSentence('This is a sentence with extra text. This is a sentence. '));
        $this->assertEquals(6, $this->TextStatistics->averageWordsPerSentence('This is a sentence with some extra text. This is a sentence. '));
    }

    /* Test Scores
    -------------------- */
    // Please note that scores for all of these sentences and scoring systems have all been calculated by hand and should therefore be accurate.
    // All values have been rounded to a single decimal point. PHP can be temperamental when it comes to floats.
    public function testFleschKincaidReadingEase()
    {
        $this->assertEquals(121.2, $this->TextStatistics->flesch_kincaid_reading_ease('This. Is. A. Nice. Set. Of. Small. Words. Of. One. Part. Each.')); // Best score possible
        $this->assertEquals(94.3, $this->TextStatistics->flesch_kincaid_reading_ease('The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(94.3, $this->TextStatistics->flesch_kincaid_reading_ease('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(94.3, $this->TextStatistics->flesch_kincaid_reading_ease('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog'));
        $this->assertEquals(94.3, $this->TextStatistics->flesch_kincaid_reading_ease("The quick brown fox jumps over the lazy dog. \n\n The quick brown fox jumps over the lazy dog."));
        $this->assertEquals(50.5, $this->TextStatistics->flesch_kincaid_reading_ease('Now it is time for a more complicated sentence, including several longer words.'));
    }

    public function testFleschKincaidGradeLevel()
    {
        $this->assertEquals(-3.4, $this->TextStatistics->flesch_kincaid_grade_level('This. Is. A. Nice. Set. Of. Small. Words. Of. One. Part. Each.')); // Best score possible
        $this->assertEquals(2.3, $this->TextStatistics->flesch_kincaid_grade_level('The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(2.3, $this->TextStatistics->flesch_kincaid_grade_level('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(2.3, $this->TextStatistics->flesch_kincaid_grade_level('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog'));
        $this->assertEquals(2.3, $this->TextStatistics->flesch_kincaid_grade_level("The quick brown fox jumps over the lazy dog. \n\n The quick brown fox jumps over the lazy dog."));
        $this->assertEquals(9.4, $this->TextStatistics->flesch_kincaid_grade_level('Now it is time for a more complicated sentence, including several longer words.'));
    }

    public function testGunningFogScore()
    {
        $this->assertEquals(0.4, $this->TextStatistics->gunning_fog_score('This. Is. A. Nice. Set. Of. Small. Words. Of. One. Part. Each.')); // Best possible score
        $this->assertEquals(3.6, $this->TextStatistics->gunning_fog_score('The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(3.6, $this->TextStatistics->gunning_fog_score('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(3.6, $this->TextStatistics->gunning_fog_score("The quick brown fox jumps over the lazy dog. \n\n The quick brown fox jumps over the lazy dog."));
        $this->assertEquals(3.6, $this->TextStatistics->gunning_fog_score('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog'));
        $this->assertEquals(14.4, $this->TextStatistics->gunning_fog_score('Now it is time for a more complicated sentence, including several longer words.'));
        $this->assertEquals(8.3, $this->TextStatistics->gunning_fog_score('Now it is time for a more Complicated sentence, including Several longer words.')); // Two proper nouns, ignored
    }

    public function testColemanLiauIndex()
    {
        $this->assertEquals(3.0, $this->TextStatistics->coleman_liau_index('This. Is. A. Nice. Set. Of. Small. Words. Of. One. Part. Each.')); // Best possible score would be if all words were 1 character
        $this->assertEquals(7.1, $this->TextStatistics->coleman_liau_index('The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(7.1, $this->TextStatistics->coleman_liau_index('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(7.1, $this->TextStatistics->coleman_liau_index("The quick brown fox jumps over the lazy dog. \n\n The quick brown fox jumps over the lazy dog."));
        $this->assertEquals(7.1, $this->TextStatistics->coleman_liau_index('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog'));
        $this->assertEquals(13.6, $this->TextStatistics->coleman_liau_index('Now it is time for a more complicated sentence, including several longer words.'));
    }

    public function testSMOGIndex()
    {
        $this->assertEquals(3.3, $this->TextStatistics->smog_index('This. Is. A. Nice. Set. Of. Small. Words. Of. One. Part. Each.')); // Should be 1.8 for any text with no words of 3+ syllables
        $this->assertEquals(3.3, $this->TextStatistics->smog_index('The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(3.3, $this->TextStatistics->smog_index('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(3.3, $this->TextStatistics->smog_index("The quick brown fox jumps over the lazy dog. \n\n The quick brown fox jumps over the lazy dog."));
        $this->assertEquals(3.3, $this->TextStatistics->smog_index('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog'));
        $this->assertEquals(13.2, $this->TextStatistics->smog_index('Now it is time for a more complicated sentence, including several longer words.'));
    }

    public function testAutomatedReadabilityIndex()
    {
        $this->assertEquals(-5.6, $this->TextStatistics->automated_readability_index('This. Is. A. Nice. Set. Of. Small. Words. Of. One. Part. Each.'));
        $this->assertEquals(1.4, $this->TextStatistics->automated_readability_index('The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(1.4, $this->TextStatistics->automated_readability_index('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog.'));
        $this->assertEquals(1.4, $this->TextStatistics->automated_readability_index("The quick brown fox jumps over the lazy dog. \n\n The quick brown fox jumps over the lazy dog."));
        $this->assertEquals(1.4, $this->TextStatistics->automated_readability_index('The quick brown fox jumps over the lazy dog. The quick brown fox jumps over the lazy dog'));
        $this->assertEquals(8.6, $this->TextStatistics->automated_readability_index('Now it is time for a more complicated sentence, including several longer words.'));
    }
}
