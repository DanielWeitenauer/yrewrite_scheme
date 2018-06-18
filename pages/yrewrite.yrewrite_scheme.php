<?php
$addon = rex_addon::get('yrewrite_scheme');
$content = '';
$buttons = '';
// Einstellungen speichern
if (rex_post('formsubmit', 'string') == '1') {
    $addon->setConfig(rex_post('config', [
        ['suffix', 'string'],
        ['scheme', 'string'],
    ]));
    echo rex_view::success($addon->i18n('config_saved'));
    if (rex::getVersion() == "5.6.0")
    {
       rex_config::save(); // REX 5.6.0 Save Fix
    }
    rex_delete_cache();
}
// BESTIMMUNG DES SUFFIX
$formElements = [];
$n = [];
$n['label'] = '<label for="rex-urlreplacer-suffix">' . $addon->i18n('suffix') . '</label>';
$select = new rex_select();
$select->setId('rex-urlreplacer-suffix');
$select->setAttribute('class', 'form-control selectpicker');
$select->setName('config[suffix]');
$select->addOption($addon->i18n('oSuf'), Null);
$select->addOption($addon->i18n('hSuf'), '.html');
$select->addOption($addon->i18n('zSuf'), '/');

$select->setSelected($addon->getConfig('suffix'));
$n['field'] = $select->get();
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// BESTIMMUNG DER METHODE

$formElements = [];
$n = [];
$n['label'] = '<label for="rex-urlreplacer-scheme">' . $addon->i18n('scheme') . '</label>';
$select = new rex_select();
$select->setId('rex-urlreplacer-scheme');
$select->setAttribute('class', 'form-control selectpicker');
$select->setName('config[scheme]');
$select->addOption($addon->i18n('standard'), 'yrewrite_scheme_suffix');
$select->addOption('URLReplace Variante 1', 'yrewrite_scheme_urlreplace');
$select->addOption('URLReplace Variante 2', 'yrewrite_scheme_nomatter');
$select->addOption('One-level', 'yrewrite_one_level');
$select->addOption('REDAXO 3/4.x - ArtID-ClangID-artikel', 'yrewrite_classic_mode');

$select->setSelected($addon->getConfig('scheme'));
$n['field'] = $select->get();
$formElements[] = $n;

$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$content .= $fragment->parse('core/form/container.php');

// Save-Button
$formElements = [];
$n = [];
$n['field'] = '<button class="btn btn-save rex-form-aligned" type="submit" name="save" value="' . $addon->i18n('config_save') . '">' . $addon->i18n('config_save') . '</button>';
$formElements[] = $n;
$fragment = new rex_fragment();
$fragment->setVar('elements', $formElements, false);
$buttons = $fragment->parse('core/form/submit.php');
$buttons = '
<fieldset class="rex-form-action">
    ' . $buttons . '
</fieldset>
';
// Ausgabe Formular
$fragment = new rex_fragment();
$fragment->setVar('class', 'edit');
$fragment->setVar('title', $addon->i18n('config'));
$fragment->setVar('body', $content, false);
$fragment->setVar('buttons', $buttons, false);
$output = $fragment->parse('core/page/section.php');
$output = '
<form action="' . rex_url::currentBackendPage() . '" method="post">
<input type="hidden" name="formsubmit" value="1" />
    ' . $output . '
</form>
';

echo $output;
// Ausgabe Hilfe
$file = rex_file::get(rex_path::addon('yrewrite_scheme','README.md'));
$body = rex_markdown::factory()->parse($file);
$fragment = new rex_fragment();
$fragment->setVar('title', $addon->i18n('help'));
$fragment->setVar('body', $body, false);
$content = $fragment->parse('core/page/section.php');
echo $content;
?>
