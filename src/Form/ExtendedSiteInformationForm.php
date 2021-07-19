<?php

namespace Drupal\api_key\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;


class ExtendedSiteInformationForm extends SiteInformationForm {
 
   /**
   * {@inheritdoc}
   */
	public function buildForm(array $form, FormStateInterface $form_state) {
		$site_config = $this->config('system.site');
		$form =  parent::buildForm($form, $form_state);
		$form['site_information']['siteapikey'] = [
			'#type' => 'textfield',
			'#title' => t('Site API Key'),
			'#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
			'#description' => t("Custom field to set the API Key"),
		];
		// Change form submit button text to 'Update Configuration'
		$form['actions']['submit']['#value'] = t('Update configuration');
		return $form;
	}
	
	public function submitForm(array &$form, FormStateInterface $form_state) {
		$apikeyval = $form_state->getValue(['siteapikey']);
		$this->config('system.site')
			->set('siteapikey', $apikeyval)
			->save();
		parent::submitForm($form, $form_state);
		// Add message that Site API Key has been set
    drupal_set_message("Successfully set Site API Key to " . $apikeyval);
	}
}