# TDS Provider Bundle

### How to install

1. `composer require firelink-media/tds-provider-bundle`
2. ensure you project has access to next env vars:
    * `SLACK_URL` - channel for tds related errors 
    * `TDS_DOMAIN` - domain which we want to connect to TDS. If set to `null`, domain will be taken from request.
    * `TDS_API_URL` - api url of TDS
    * `TDS_API_KEY` - TDS api key
3. If you want to apply `LandingPageCookieSetterSubscriber` to set first user's url on site to `landing_page` cookie, you must add next part of code to your project `config/services.yaml`:
```
  TdsProviderBundle\EventSubscriber\LandingPageCookieSetterSubscriber:
           tags: [ { name: kernel.event_listener} ]
```
