{capture name=path}{l s='Send us inspiration' mod='socialmediaphotos'}{/capture}
</div>
</div>
</div>

<div class="socialsharecms">
<div class="container">
<div class="col-md-12 no-padding">
<h3 class="page-heading bottom-indent">
	{l s='Get 40 loyality point' mod='socialmediaphotos'}
</h1>
{* test: {$last_sent_inspiration} vs {time()}
<br/>
roznica:<br/>
{$time_delay_inspiration} [s] vs {$config_time_delay_inspiration}
{math equation="x - y" x=time() y=$last_sent_inspiration} [s] *}
<h1 class="page-subheading">{l s='Send your inspiration photo' mod='socialmediaphotos'}</h3>
        <div class="left_image_inspiration"></div>
        <div class="right_image_inspiration"></div>
	{include file="$tpl_dir./errors.tpl"}
    {* action="{$base_dir}/index.php?fc=module&module=socialmediaphotos&controller=share&send=success" *}
	<form  method="post" class="contact-form-box" enctype="multipart/form-data">
		<fieldset>
        {if $logged}
{else}
<div class="login_panel">
<div class="panel_inside">
<p><i class="icon icon-sign-in"></i></p>
{* {if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}index.php?controller=authentication *}
    <a href="{$link->getPageLink('authentication', true)|escape:'html':'UTF-8'}" class="button btn btn-default standard-checkout button-medium">{l s="Login" mod='socialmediaphotos'}</a>
</div>
</div>
{/if}
			<div class="clearfix">
            <div class="row">
            {if isset($error)}
            <div class="alert alert-danger">
                {$error}
            </div>
            {/if}
         {if isset($success)}
            <div class="alert alert-success">
            {$success}
            </div>
        {/if}
				<div class="col-xs-12 col-md-12">
                    <div class="form-group selector1">
						<label for="mediaphotos_photo">{l s='Image files' mod='socialmediaphotos'}*</label>
                        <p>{l s="Availble formats is JPG JPEG PNG" mod='socialmediaphotos'}</p>
                        <input type="file" class="form-control" id="mediaphotos_photo" name="mediaphotos_photo[]" multiple value="" required  />
					</div> 
                    <div class="row">
                    <div class="col-md-9">
                    {if isset($uploaded)}
                    {foreach $uploaded as $upload} 
                    {* uploaded: {$uploaded|print_r} *}
                    <div class="thumbnail col-md-3">
                        <div class="uploaded_photo" style="background-image:url('{if isset($force_ssl) && $force_ssl}{$base_dir_ssl}{else}{$base_dir}{/if}/modules/socialmediaphotos/controllers/front/uploads/pictures/{$upload}')">
                        </div>
                    </div>
                    {/foreach}
                    {else}
                        {for $item=1 to 4}
                            <div class="thumbnail col-md-3">
                                <div class="uploaded_photo">
                                    <i class="icon icon-image"></i>
                                </div>
                            </div>
                        {/for}
                    {/if}
                    </div>
                    </div>
                    <div class="form-group selector1">
						<label for="mediaphotos_socialurl">{l s='URL where post is shared with tag' mod='socialmediaphotos'}*</label>
                        <p>{l s="To get loyality point you need to share photo on social media" mod='socialmediaphotos'}</p>
                        <input type="text" class="form-control" required id="mediaphotos_socialurl" placeholder="{l s="Link to facebook post or instagram photo" mod='socialmediaphotos'}" name="mediaphotos_socialurl" value=""  />
					</div>
                    </div>
                    </div>
                    <div class="submit text-center">
				<input type="hidden" name="mediaphotosKey" value="#" />

                {if $time_delay_inspiration < $config_time_delay_inspiration}
                <button disabled type="submit" name="submitMessage" id="submitMessage" class="button btn btn-default button-medium">
                <span>{l s='Send inspiration' mod='socialmediaphotos'} <span class="available_try" style="display: inline;padding: 0;">({l s='available for' mod='socialmediaphotos'} <span class="odliczanie" style="display: inline-block;padding: 0;" data-mode="minutes" data-promotion="{$time_on_inspiration}"></span>)</span><i class="icon-chevron-right right"></i></span>
                </button>
                {else}
                <button type="submit" name="submitMessage" id="submitMessage" class="button btn btn-default button-medium">
                <span>{l s='Send inspiration' mod='socialmediaphotos'}<i class="icon-chevron-right right"></i></span>
                </button>
                {/if}
                
                <div class="clearfix"></div>
                			</div>
                <label><input type="checkbox" value="1" name="mediaphotos_conditions" required> 
                {l s="I was reading and agree with terms and conditions" mod='socialmediaphotos'}
                </label>
                <div class="clearfix"></div>
    <label>
    * {l s="required" mod='socialmediaphotos'}
    </label>
		</fieldset>
        	<div class="footer">
            {* {l s="Footer informations" mod='socialmediaphotos'} *}
            </div>	
            <input type="hidden" class="form_choose_file" name="form_choose_file" value="{l s='Choose File' mod='socialmediaphotos'}" />	
            <input type="hidden" class="form_nofiles_selected" name="form_nofiles_selected" value="{l s='No files selected' mod='socialmediaphotos'}" />	
	</form>
    </div>
    </div>
</div>
</div>
{*{/if}*}
{addJsDefL name='mediaphotos_photo'}{l s='No file selected' mod='socialmediaphotos' js=1}{/addJsDefL}
{addJsDefL name='mediaphotos_photo'}{l s='Choose File' mod='socialmediaphotos' js=1}{/addJsDefL}
<script type="text/javascript">
    $.uniform.defaults.fileButtonHtml = "{l s='Choose File' mod='socialmediaphotos'}";
    $.uniform.defaults.fileDefaultHtml = "{l s='No files selected' mod='socialmediaphotos'}";
</script>
<div>
<div class="container">
<div class="col-md-12">