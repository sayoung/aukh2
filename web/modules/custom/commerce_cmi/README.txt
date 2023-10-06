*************************************************************************************************************************
* Object : CMI Drupal Commerce plugin											*
*														 	*
* Version :  V1.0 of February 2019											*
*															* 
* Copyright (c) 2019 Centre Monétique Interbancaire - CMI. All rights reserved.						*
* 															*
*************************************************************************************************************************


CONTENTS OF THIS FILE
---------------------
* Introduction
* Requirements
* Installation
* Configuration


INTRODUCTION
------------
This module allows Drupal Commerce customers to pay using the CMI online payment module.


REQUIREMENTS
------------
This module's setup requires the following:
* Submodules of Drupal Commerce package (https://drupal.org/project/commerce)
  - Commerce core
  - Commerce Payment (and its dependencies)

For this module to be operational, you need to use the information that are sent to you by CMI in the integration kit.

  
INSTALLATION
------------
 1. Download and copy the 'commerce_cmi' folder into your modules directory or navigate to '/admin/modules/install' and upload the module 
 then click Install to upload and unpack the new module on the server. The files are being downloaded to the modules directory.
 
 2. Enable the module under '/admin/modules' within the group 'Commerce (contrib)'.
 
 
CONFIGURATION
-------------
* Create a new CMI payment gateway.
  Administration > Commerce > Configuration > Payment gateways > Add payment gateway
  
* Fill some mandatory information that are sent to you by CMI in the integration kit:
  - Gateway: Gateway URL provided by CMI in the integration kit.
  - ClientId: Merchant ID provided by CMI in the integration kit.
  - Clé de hachage: Hash key that you are supposed to set in your CMI back office.
  - Mode de confirmation : 
	. Automatic: If you wish that the transactions must be confirmed automatically to debit the customers. 
	. Manual: If you wish to confirm the transactions manually via your CMI back office.
