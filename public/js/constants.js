/**
 * Root folder del webserver.
 * Il suo valore deve essere sempre "<tt>/</tt>". <b>NON MODIFICARE!</b>
 * @type {string}
 */
const WEBSERVER_ROOT = "/";

/*
 * Riferimenti ad alcune pagine chiave del sito.
 */

const HOME_PAGE = WEBSERVER_ROOT;
const SIGNIN_PAGE = WEBSERVER_ROOT + "login";
// NB Questo non apre la pagina di login sul tab signup (bisognerebbe aggiungere uno script)
// https://stackoverflow.com/questions/7862233/twitter-bootstrap-tabs-go-to-specific-tab-on-page-reload-or-hyperlink?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
const SIGNUP_PAGE = SIGNIN_PAGE + "#signup";
const CHARTS_PAGE = WEBSERVER_ROOT + "charts.php";
const UPLOAD_PAGE = WEBSERVER_ROOT + "upload.php";