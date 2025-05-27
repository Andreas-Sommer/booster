<?php

namespace Belsignum\Booster\Userfuncs;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class ContentTca
{
    public function dynamicTitleByType(&$parameters)
    {
        $ctrl = $parameters['parent']['config']['overrideChildTca']['ctrl'];
        $labelKey = $ctrl['label'] ?: $GLOBALS['TCA']['tx_booster_domain_model_content']['ctrl']['label'];
        $label = $parameters['row'][$labelKey];
        $parameters['title'] = $ctrl['label_static'] ?: $label;

        if ($ctrl['label_alt_force'] && $ctrl['label_static'] === NULL)
        {
            $labelAlts = GeneralUtility::trimExplode(',', $ctrl['label_alt']);
            foreach ($labelAlts as $_ => $labelAlt)
            {
                $parameters['title'] .= ',' . $parameters['row'][$labelAlt];
            }
        }
    }
}
