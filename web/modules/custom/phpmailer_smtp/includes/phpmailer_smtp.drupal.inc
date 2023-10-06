<?php

/**
 * @file
 * Implements PHPMailer support on behalf of Drupal core.
 */

use Drupal\phpmailer_smtp\Plugin\Mail\PhpMailerSmtp;

/**
 * Sends an already formatted e-mail message composed by drupal_mail().
 *
 * @param array $message
 *   A message array, as described in hook_mail_alter().
 *
 * @return bool
 *   TRUE if the mail was successfully accepted, otherwise FALSE.
 *
 * @see PhpMailerSmtp::mail()
 *
 * @todo Consider to either drop this entirely, or move into phpmailer_smtp.module
 *   as singleton to instantiate the PhpMailerSmtp class.
 *
 * @todo This might be totally unnecessary in Drupal 8, but testing needs to be
 *   done to verify.
 */
function phpmailer_smtp_send(array $message) {
  $mail = &drupal_static(__FUNCTION__);

  if (!isset($mail)) {
    $mail = new PhpMailerSmtp();
  }

  return $mail->mail($message);
}
