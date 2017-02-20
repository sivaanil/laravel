<?php

namespace Unified\Http\Controllers\Api\Response;

/**
 * Response codes.
 *
 * @author Igor Kondrakhin <igor.kondrakhin@csquaredsystems.com>
 */
final class ResponseCode {
    
    // Standard response for successful HTTP requests.
    // The actual response will depend on the request method used.
    // In a GET request, the response will contain an entity corresponding to
    // the requested resource.
    // In a POST request, the response will contain an entity describing or
    // containing the result of the action
    const OK = 200;
    
    // The request has been fulfilled, resulting in the creation of a new resource
    const CREATED = 201;
    
    // The server successfully processed the request and is not returning any content
    const NO_CONTENT = 204;
    
    // This requests should be directed to the given URI
    const MOVED_PERMANENTLY = 301;
    
    // The server cannot or will not process the request due to an apparent client
    // error (e.g., malformed request syntax, invalid request message framing, or
    // deceptive request routing)
    const BAD_REQUEST = 400;
    
    // Similar to 403 Forbidden, but specifically for use when authentication is
    // required and has failed or has not yet been provided. The response must
    // include a WWW-Authenticate header field containing a challenge applicable
    // to the requested resource.
    const UNAUTHORIZED = 401;
    
    // The request was a valid request, but the server is refusing to respond
    // to it. 403 error semantically means "unauthorized", i.e. the user does
    // not have the necessary permissions for the resource
    const FORBIDDEN = 403;
    
    // The requested resource could not be found but may be available again in
    // the future. Subsequent requests by the client are permissible
    const NOT_FOUND = 404;
    
    // The requested resource is capable of generating only content not acceptable
    // according to the Accept headers sent in the request
    const NOT_ACCEPTABLE = 406;
    
    // A request method is not supported for the requested resource; for example, a
    // GET request on a form which requires data to be presented via POST, or a PUT
    // request on a read-only resource.
    const METHOD_NOT_ALLOWED = 405;
    
    // The request was well-formed but was unable to be followed due to semantic errors.
    const UNPROCESSABLE_ENTITY = 422;
    
    // The user has sent too many requests in a given amount of time.
    // Intended for use with rate limiting schemes
    const TOO_MANY_REQUESTS = 429;
    
    // A generic error message, given when an unexpected condition was encountered
    // and no more specific message is suitable
    const INTERNAL_ERROR = 500;
    
    // The server either does not recognize the request method, or it lacks the
    // ability to fulfill the request. Usually this implies future availability
    // (e.g., a new feature of a web-service API)
    const NOT_IMPLEMENTED = 501;
}
