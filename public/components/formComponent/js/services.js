app.factory('formService', function($resource) {
  return $resource(
    'quote', 
    null,
    {
      'get': {
        'method': "GET",
        'params': {
          'sp': '0'
        }
      }
    }
  );
});