# Lightning API
Lightning API provides a standard API with authentication and authorization
that allows for easy ingestion of content by other applications. It primarily
makes use of the json:api and OAuth2 standards via the
[JSON API](https://www.drupal.org/project/jsonapi) and
[Simple Oauth](https://www.drupal.org/project/simple_oauth) modules.

#### Known Issues
* For security reasons, the JSON API module does not support creating or
  modifying config entities via the API.
