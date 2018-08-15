(function RDStationIntegration() {
  var SERVER_ORIGIN = 'https://wp.rd.services';
  var CLIENT_ID = 'c9d14ec8-2671-404e-b337-ebae63906a8b';
  var REDIRECT_URL = 'https://wp.rd.services/prod/oauth/callback';
  var LEGACY_TOKENS_ENDPOINT = 'https://api.rd.services/platform/legacy/tokens';
  var AUTHENTICATION_ENDPOINT = 'https://api.rd.services/auth/dialog';
  var newWindowInstance = null;
  var settings;

  function oauthIntegration(message) {
    if (message.origin === SERVER_ORIGIN) {
      persist(message);

      if (newWindowInstance) {
        newWindowInstance.close();
      }
    }
  }

  function bindConnectButton() {
    var button = document.querySelector('.rd-oauth-integration');
    button.addEventListener('click', function () {
      newWindowInstance = window.open(AUTHENTICATION_ENDPOINT + '?client_id=' + CLIENT_ID + '&;redirect_url=' + REDIRECT_URL, '_blank')
    })
  }

  function bindDisconnectButton() {
    var disconnectButton = document.querySelector('.rd-oauth-disconnect');

    disconnectButton.addEventListener('click', function() {
      var data = { action: 'rdsm-disconnect-oauth' };

      jQuery.ajax({
        method: "POST",
        url: ajaxurl,
        data: data,
        success: function() {
          settings.displayDisconnectedAccountElements();
        }
      });
    })
  }

  function listenForMessage() {
    window.addEventListener('message', oauthIntegration);
  }

  function persist(message) {
    jQuery(document).ready(function ($) {
      var tokens = JSON.parse(message.data);
      var data = {
        action: 'rd-persist-tokens',
        accessToken: tokens.accessToken,
        refreshToken: tokens.refreshToken
      };

      jQuery.ajax({
        method: "POST",
        url: ajaxurl,
        data: data,
        success: function() {
          settings.displayConnectedAccountElements();
          persistLegacyTokens(tokens.accessToken)
        }
      });
    });
  }

  function persistLegacyTokens(accessToken) {
    jQuery.ajax({
      url: LEGACY_TOKENS_ENDPOINT,
      headers: { 'Authorization':'Bearer ' + accessToken },
      method: 'GET',
      success: function(data) {
        data.action = 'rd-persist-legacy-tokens';
        jQuery.post(ajaxurl, data);
      }
    });
  }

  function setupSettings() {
    settings = new RDSMGeneralSettings();
    settings.toggleElementsDisplay();
  }

  function init() {
    setupSettings();
    bindConnectButton();
    bindDisconnectButton();
    listenForMessage();
  }

  window.addEventListener('DOMContentLoaded', init);
})();
