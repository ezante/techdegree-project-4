<?php

class Game
{
    private $phrase;
    private $lives = 5;

    public function __construct(Phrase $phrase)
    {
        $this->phrase = $phrase;
    }

    /**
     * Generate HTML for keyboard.
     */
    public function displayKeyboard()
    {
        $keyboard = [
            ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p'],
            ['a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l'],
            ['z', 'x', 'c', 'v', 'b', 'n', 'm'],
        ];

        $html = '';
        foreach ($keyboard as $row) {
            $html .= '<div class="keyrow">';
            $html .= implode(array_map(fn ($key) => $this->checkKey($key), $row));
            $html .= '</div>';
        }

        return <<<HTML
            <form action="play.php" method="post">
                <div id="qwerty" class="section">
                    {$html}
                </div>
            </form>
        HTML;
    }

    /**
     * Generate HTML for scoreboard.
     */
    public function displayScore()
    {
        $lives = str_repeat(
            <<<HTML
                <li class="tries">
                    <img src="images/liveHeart.png" height="35px" widght="30px">
                </li>
            HTML,
            $this->livesLeft()
        );

        return <<<HTML
            <div id="scoreboard" class="section">
                <ol>
                    {$lives}
                </ol>
            </div>
        HTML;
    }

    /**
     * Generate HTML for key.
     */
    public function checkKey($letter)
    {
        $letterPlayed = in_array($letter, $this->phrase->selected);
        $letterCorrect = $this->phrase->checkLetter($letter);

        $result = $letterPlayed
                        ? ($letterCorrect ? 'correct' : 'incorrect')
                        : '';

        return <<<HTML
            <button type="submit" name="key" value="{$letter}" class="key {$result}">
                {$letter}
            </button>
        HTML;
    }

    /**
     * Check if game is over and return congrats or lose message.
     */
    public function gameOver()
    {
        $message = $this->checkForWin()
                        ? 'Congratulations on guessing!'
                        : 'Better luck next time!';

        $color = $this->checkForWin()
                        ? '#84e05a'
                        : '#fa6450';

        if ($this->checkForWin() || $this->checkForLose()) {
            return <<<HTML
                <h1>Phrase was: {$this->phrase->currentPhrase}. {$message}</h1>
                <script>document.body.style.backgroundColor = '{$color}'</script>
            HTML;
        }

        return false;
    }

    /**
     * Count if all letters are guessed and game is won.
     */
    public function checkForWin()
    {
        $phraseLettersUnique = $this->phrase->getPhraseLettersArray();
        $guessedLettersUnique = $this->phrase->getGuessedLettersArray();
        $correctLettersUnique = array_intersect($phraseLettersUnique, $guessedLettersUnique);

        return count($phraseLettersUnique) === count($correctLettersUnique);
    }

    /**
     * Check if all lives have been used and game is lost.
     */
    public function checkForLose()
    {
        return $this->livesLeft() < 1;
    }

    /**
     * Count lives left in the game.
     */
    public function livesLeft()
    {
        return $this->lives-$this->wrongAnswers();
    }

    /**
     * Count number of wrong answers.
     */
    public function wrongAnswers()
    {
        return count(
            array_diff(
                $this->phrase->getGuessedLettersArray(),
                $this->phrase->getPhraseLettersArray()
            )
        );
    }

    /**
     * Generate "start new game" HTML.
     */
    public function restartGame()
    {
        return <<<HTML
            <form action="play.php" method="post">
                <input id="btn__reset" class="btn" type="submit" name="start" value="Start Game">
            </form>
        HTML;
    }
}
