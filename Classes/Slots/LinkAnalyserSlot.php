<?php

namespace T3\Dce\Slots;

/*  | This extension is made with love for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2012-2023 Armin Vieweg <armin@v.ieweg.de>
 */
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Linkvalidator\LinkAnalyzer;

/**
 * Class LinkAnalyserSlot.
 */
class LinkAnalyserSlot
{
    /**
     * @param string $table
     */
    public function beforeAnalyzeRecord(
        array $results,
        array $record,
        $table,
        array $fields,
        LinkAnalyzer $linkAnalyser
    ): array {
        if ('tt_content' === $table && !empty($record['pi_flexform'])) {
            $rawRecord = BackendUtility::getRecord('tt_content', $record['uid'], '*');
            if (0 !== strpos($rawRecord['CType'], 'dce_')) {
                return [$results, $record, $table, $fields, $linkAnalyser];
            }
            /** @var array|string $flexformArray */
            $flexformArray = GeneralUtility::xml2array($record['pi_flexform']);
            if (is_string($flexformArray)) {
                return [$results, $record, $table, $fields, $linkAnalyser];
            }
            $flexformData = ArrayUtility::flatten($flexformArray);

            $newFlexformContent = '';
            foreach ($flexformData as $key => $fieldValue) {
                if (!StringUtility::endsWith($key, 'vDEF')) {
                    continue;
                }

                if (!empty($fieldValue) && !is_numeric($fieldValue) && false !== strpos($fieldValue, '://')) {
                    // Check for typolink (string, without new lines or < > signs)
                    if (\is_string($fieldValue) &&
                        false === strpos($fieldValue, "\n") &&
                        false === strpos($fieldValue, ' ') &&
                        false === strpos($fieldValue, '<') &&
                        false === strpos($fieldValue, '>')
                    ) {
                        $fieldValue = '<a href="' . $fieldValue . '">Typolink</a>';
                    }
                    $newFlexformContent .= $fieldValue . "\n\n";
                }
            }
            $record['pi_flexform'] = $newFlexformContent;
            $GLOBALS['TCA'][$table]['columns']['pi_flexform']['config']['softref'] = 'typolink_tag,url';
        }

        return [$results, $record, $table, $fields, $linkAnalyser];
    }

    /**
     * @param \TYPO3\CMS\Linkvalidator\Event\BeforeRecordIsAnalyzedEvent $event
     */
    public function dispatchEvent($event): void
    {
        [$results, $record] = $this->beforeAnalyzeRecord(
            $event->getResults(),
            $event->getRecord(),
            $event->getTableName(),
            $event->getFields(),
            $event->getLinkAnalyzer()
        );
        $event->setResults($results);
        $event->setRecord($record);
    }
}
