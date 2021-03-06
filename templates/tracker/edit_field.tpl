<form class="simple" method="post" action="{service controller=tracker action=edit_field}">
	<div class="accordion">
		<h4>{tr}General{/tr}</h4>
		<div>
			<label>
				{tr}Name:{/tr}
				<input type="text" name="name" value="{$field.name|escape}" required="required">
			</label>
			<label>
				{tr}Description:{/tr}
				<textarea name="description">{$field.description|escape}</textarea>
			</label>
			<label>
				<input type="checkbox" name="description_parse" value="1"
					{if $field.descriptionIsParsed eq 'y'}checked="checked"{/if}
					>
				{tr}Description contains wiki syntax{/tr}
			</label>
		</div>

		<h4>{tr _0=$info.name}Options for %0{/tr}</h4>

		<div>
			
			<p>{$info.description|escape}</p>

			{if $field.type eq 't' or $field.type eq 'a'}
				{* Pretend the field attribute is just an option as it only exists for two field types *}
				<label>
					<input type="checkbox" name="multilingual" value="1"
						{if $field.isMultilingual eq 'y'}checked="checked"{/if}>
					{tr}Multilingual{/tr}
				</label>
			{/if}

			{foreach from=$info.params key=param item=def}
				<label>
					{$def.name|escape}
					{if $def.options}
						<select name="option~{$param|escape}">
							{foreach from=$def.options key=val item=label}
								<option value="{$val|escape}"
									{if $options[$param] eq $val} selected="selected"{/if}>
									{$label|escape}
								</option>
							{/foreach}
						</select>
					{elseif $def.separator}
						<input type="text" name="option~{$param|escape}" value="{$options[$param]|implode:$def.separator|escape}">
					{elseif $def.count eq '*'}
						<input type="text" name="option~{$param|escape}" value="{$options[$param]|implode:','|escape}">
					{else}
						<input type="text" name="option~{$param|escape}" value="{$options[$param]|escape}">
					{/if}
					{if $def.count eq '*'}
						<div class="description">{tr}Separate multiple with commas.{/tr}</div>
					{/if}
					<div class="description">{$def.description|escape}</div>
				</label>
			{/foreach}
		</div>

		<h4>{tr}Validation{/tr}</h4>

		<div>
			<label>
				{tr}Type{/tr}
				<select name="validation_type">
					{foreach from=$validation_types key=type item=label}
						<option value="{$type|escape}"
							{if $type eq $field.validation} selected="selected"{/if}>
							{$label|escape}
						</option>
					{/foreach}
				</select>
			</label>

			<label>
				{tr}Parameters{/tr}
				<input type="text" name="validation_parameter" value="{$field.validationParam|escape}">
			</label>

			<label>
				{tr}Error Message{/tr}
				<input type="text" name="validation_message" value="{$field.validationMessage|escape}">
			</label>
		</div>

		<h4>{tr}Permissions{/tr}</h4>

		<div>
			<label>
				{tr}Visibility{/tr}
				<select name="visibility">
					<option value="n"{if $field.isHidden eq 'n'} selected="selected"{/if}>{tr}Visible by all{/tr}</option>
					<option value="r"{if $field.isHidden eq 'r'} selected="selected"{/if}>{tr}Visible by all but not in RSS feeds{/tr}</option>
					<option value="y"{if $field.isHidden eq 'y'} selected="selected"{/if}>{tr}Visible by administrators only{/tr}</option>
					<option value="p"{if $field.isHidden eq 'p'} selected="selected"{/if}>{tr}Editable by administrators only{/tr}</option>
					<option value="c"{if $field.isHidden eq 'c'} selected="selected"{/if}>{tr}Editable by administrators and creator only{/tr}</option>
					<option value="i"{if $field.isHidden eq 'i'} selected="selected"{/if}>{tr}Immutable after creation{/tr}</option>
				</select>
				<div class="description">
					{tr}Creator requires a user field with auto-assign to creator (1){/tr}
				</div>
			</label>

			<label>
				{tr}Visible by{/tr}
				<input type="text" class="groupselector" name="visible_by"
					value="{foreach from=$field.visibleBy item=group}{$group|escape}, {/foreach}">
			</label>

			<label>
				{tr}Editable by{/tr}
				<input type="text" class="groupselector" name="editable_by"
					value="{foreach from=$field.editableBy item=group}{$group|escape}, {/foreach}">
			</label>
			
			<label>
				{tr}Error Message{/tr}
				<input type="text" name="error_message" value="{$field.errorMsg|escape}">
			</label>
		</div>

		<h4>{tr}Advanced{/tr}</h4>

		<div>
			<label>
				{tr}Permanent Name:{/tr}
				<input type="text" name="permName" value="{$field.permName|escape}" pattern="[a-zA-Z0-9_]+">
				<div class="description">
					{tr}Changing the permanent name may have consequences in integrated systems.{/tr}
				</div>
			</label>
			{if $prefs.tracker_change_field_type eq 'y'}
				<label>
					{tr}Field Type:{/tr}
					<select name="type" data-original="{$field.type}" class="confirm-prompt">
						{foreach from=$types key=k item=info}
							<option value="{$k|escape}"
								{if $field.type eq $k}selected="selected"{/if}>
								{$info.name|escape}
								{if $info.deprecated}- Deprecated{/if}
							</option>
						{/foreach}
					</select>
					{foreach from=$types item=info key=k}
						<div class="description {$k|escape}" style="display: none;">
							{$info.description|escape}
							{if $info.help}
								<a href="{$prefs.helpurl|escape}{$info.help|escape:'url'}" target="tikihelp" class="tikihelp" title="{$info.name|escape}">
									{icon _id=help alt=''}
								</a>
							{/if}
						</div>
					{/foreach}
					{jq}
$('select[name=type]').change(function () {
	var descriptions = $(this).closest('label').
			find('.description:not(.warning)').
			hide();

	if ($(this).val()) {
		descriptions
			.filter('.' + $(this).val())
			.show();
	}
}).change();
{/jq}
					<div class="description warning">
						{icon _id='error' alt="{tr}Warning{/tr}"} {tr}Changing the field type may cause irretrievable data loss - use with caution!{/tr}
					</div>
				</label>
			{/if}
		</div>
	</div>

	<div class="submit">
		<input type="submit" name="submit" value="{tr}Save{/tr}">
		<input type="hidden" name="trackerId" value="{$field.trackerId|escape}">
		<input type="hidden" name="fieldId" value="{$field.fieldId|escape}">
	</div>
</form>
{jq}
$('.accordion').removeClass('accordion').accordion({
	header: 'h4',
	heightStyle: "content"
});
{/jq}
