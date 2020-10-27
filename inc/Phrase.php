<?php

class Phrase
{
    public $currentPhrase;
    public $selected = [];
    public $phrases = [
        'Hang in there',
        'Easy does it',
        'Miss the boat',
        'Under the weather',
        'Break the ice',
        'On thin ice',
        'Keep it simple',
        'On cloud nine',
        'Cut the mustard',
    ];

    public function __construct($currentPhrase = '', $selected = [])
    {
        $this->currentPhrase = empty($currentPhrase)
                                    ? $this->phrases[array_rand($this->phrases)]
                                    : $currentPhrase;

        $this->selected = $selected;
    }

    /**
     * Generate HTML for letter placeholders.
     */
    public function addPhraseToDisplay()
    {
        $liHtml = '';
        foreach (str_split(strtolower($this->currentPhrase)) as $character) {
            $isLetter = ctype_alpha($character);
            $isSelected = in_array($character, $this->selected);

            $classes = $isLetter
                            ? ($isSelected ? 'show letter' : 'hide letter')
                            : 'space';

            $liHtml .= "<li class='{$classes}'>{$character}</li>";
        }

        return <<<HTML
            <div id="phrase" class="section">
                <ul>
                    {$liHtml}
                </ul>
            </div>
        HTML;
    }

    /**
     *  Check if letter is present in phrase.
     */
    public function checkLetter($letter)
    {
        return in_array($letter, $this->getPhraseLettersArray());
    }

    /**
     * Get all unique phrase letters.
     */
    public function getPhraseLettersArray()
    {
        $lowercase = strtolower($this->currentPhrase);
        $noWhitespace = str_replace(' ', '', $lowercase);
        $asArray = str_split($noWhitespace);

        return array_unique($asArray);
    }

    /**
     * Get all guessed phrase letters.
     */
    public function getGuessedLettersArray()
    {
        return array_unique($this->selected);
    }
}
