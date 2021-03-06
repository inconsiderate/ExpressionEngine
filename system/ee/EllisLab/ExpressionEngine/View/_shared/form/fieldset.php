<?php

// If a string is passed, just display the string
if (is_string($setting))
{
	echo $setting;
	return;
}

$grid = (isset($setting['grid']) && $setting['grid'] == TRUE);

// Gather classes needed to set on the fieldset
$fieldset_classes = '';

// First, see if there are any specified in the attributes array
if (isset($setting['attrs']['class']))
{
	$fieldset_classes = ' ' . $setting['attrs']['class'];
	unset($setting['attrs']['class']);
}

if (isset($setting['columns']))
{
	$fieldset_classes = 'w-'.$setting['columns'];
}

// Any fields required?
foreach ($setting['fields'] as $field_name => $field)
{
	if (isset($field['required']) && $field['required'] == TRUE)
	{
		$fieldset_classes .= ' fieldset-required';
		break;
	}
}
if (isset($setting['security']) && $setting['security'] == TRUE)
{
	$fieldset_classes .= ' fieldset-security-enhanced';
}
if (isset($setting['caution']) && $setting['caution'] == TRUE)
{
	$fieldset_classes .= ' fieldset-security-caution';
}
if (isset($setting['hide']) && $setting['hide'] == TRUE)
{
	$fieldset_classes .= ' hidden';
}
if ($grid)
{
	$fieldset_classes .= ' fieldset-faux';
}
else
{
	$fieldset_classes .= ' '.form_error_class(array_keys($setting['fields']), 'fieldset-invalid');
}
// If a validation result object is set, see if any of our fields have errors
if (isset($errors))
{
	foreach (array_keys($setting['fields']) as $field)
	{
		if ($errors->hasErrors($field))
		{
			$fieldset_classes .= ' fieldset-invalid';
			break;
		}
	}
}
// Individual settings can have their own groups
$setting_group = $group;
if (isset($setting['group']))
{
	$setting_group = $setting['group'];
}
if (is_array($setting_group))
{
	$setting_group = implode('|', $setting_group);
}

// Grids have to be in a div for an overflow bug in Firefox
$element = ($grid) ? 'div' : 'fieldset'; ?>
<<?=$element?> class="<?=$fieldset_classes?>" <?php if ($setting_group): ?> data-group="<?=$setting_group?>"<?php endif ?><?php if (isset($setting['attrs'])): foreach ($setting['attrs'] as $key => $value):?> <?=$key?>="<?=$value?>"<?php endforeach; endif; ?>>
	<div class="field-instruct <?=($grid) ? form_error_class(array_keys($setting['fields'])) : '' ?>">
		<?php if (isset($setting['title'])): ?>
		<label><?=lang($setting['title'])?></label>
		<?php endif; ?>
		<?php if (isset($setting['desc'])): ?>
		<em><?=lang($setting['desc'])?></em>
		<?php endif; ?>
		<?php if (isset($setting['desc_cont'])): ?>
		<em><?=lang($setting['desc_cont'])?></em>
		<?php endif; ?>
		<?php if (isset($setting['example'])): ?>
		<p><?=$setting['example']?></p>
		<?php endif; ?>
	</div>
	<div class="field-control">
		<?php
			$count = 0;
			$values = [];
			foreach ($setting['fields'] as $field_name => $field)
			{
				$field_name = isset($field['name'])
					? $field['name'] : $field_name;

				$vars = array(
					'field_name' => $field_name,
					'field' => $field,
					'setting' => $setting,
					'grid' => $grid
				);

				// If there are multiple fields with the same name, such as
				// radio options with fields in between, persist the value
				// across them, otherwise the first value in each will be checked
				if (isset($values[$field_name]) && ! isset($field['value']))
				{
					$vars['field']['value'] = $values[$field_name];
				}
				elseif (isset($field['value']))
				{
					$values[$field_name] = $field['value'];
				}

				// Add top margin to sequential fields
				if ($count > 0 && ! isset($field['margin_top']))
				{
					$vars['field']['margin_top'] = TRUE;
				}

				if ($field['type'] != 'hidden')
				{
					$count++;
				}

				$this->embed('ee:_shared/form/field', $vars);
			}
		?>
		<?php if (isset($setting['button'])): ?>
		<?php
			$button = $setting['button'];
			$rel = isset($button['rel']) ? $button['rel'] : '';
			$href = isset($button['href']) ? $button['href'] : '#';
			$for = isset($button['for']) ? $button['for'] : '';
		?>
		<a class="btn action submit js-modal-link--side" rel="<?=$rel?>" href="<?=$href?>" data-for="<?=$for?>"><?=lang($button['text'])?></a>
		<?php endif; ?>
	</div>
</<?=$element?>>
