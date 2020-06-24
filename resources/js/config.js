/**
 * Defines the API route we are using.
 */
var api_url = "";
var app_url = '';

switch (process.env.NODE_ENV) {
    case "development":
        api_url = "http://demo.test/api/v1";
        app_url = 'http://demo.test';
        break;
    case "production":
        api_url = "http://demo.test/api/v1";
        app_url = 'http://demo.test';
        break;
}

export const ROAST_CONFIG = {
    API_URL: api_url,
    APP_URL: app_url,
};
