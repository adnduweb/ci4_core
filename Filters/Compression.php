<?php

namespace Adnduweb\Ci4Core\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Compression implements FilterInterface
{
  public function before(RequestInterface $request, $params = null)  
  {
    // Do something here
  }

  //--------------------------------------------------------------------

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    $nameUri = (implode('_', $request->uri->getSegments()));
    // print_r($request->uri->getSegments());
    // exit;
    if (service('Settings')->setting_activer_multilangue == true) {
      if ($request->uri->getSegments()[0] != env('CI_AREA_ADMIN')) {

        // Do something here
        if (env('assets.compressionHtml') == true) {
          $re = '%# Collapse whitespace everywhere but in blacklisted elements.
                (?>             # Match all whitespans other than single space.
                  [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
                | \s{2,}        # or two or more consecutive-any-whitespace.
                ) # Note: The remaining regex consumes no text at all...
                (?=             # Ensure we are not in a blacklist tag.
                  [^<]*+        # Either zero or more non-"<" {normal*}
                  (?:           # Begin {(special normal*)*} construct
                    <           # or a < starting a non-blacklist tag.
                    (?!/?(?:textarea|pre|script)\b)
                    [^<]*+      # more non-"<" {normal*}
                  )*+           # Finish "unrolling-the-loop"
                  (?:           # Begin alternation group.
                    <           # Either a blacklist start tag.
                    (?>textarea|pre|script)\b
                  | \z          # or end of file.
                  )             # End alternation group.
                )  # If we made it here, we are not in a blacklist tag.
                %Six';

          $options = [
            'max-age'  => 300,
            's-maxage' => 900
          ];
          $response->setCache($options);

          $new_buffer = preg_replace($re, " ", $response->getBody());
          $response->setBody($new_buffer);
        }
      }
    } else {

      //if (!is_array($request->uri->getSegments()) && $request->uri->getSegments()[0] != env('CI_AREA_ADMIN')) {
      //var_dump($request->uri->getSegments()); exit;
      if (!in_array(env('CI_AREA_ADMIN'), $request->uri->getSegments())) {

        // Do something here
        if (env('assets.compressionHtml') == true) {
          $re = '%# Collapse whitespace everywhere but in blacklisted elements.
                (?>             # Match all whitespans other than single space.
                  [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
                | \s{2,}        # or two or more consecutive-any-whitespace.
                ) # Note: The remaining regex consumes no text at all...
                (?=             # Ensure we are not in a blacklist tag.
                  [^<]*+        # Either zero or more non-"<" {normal*}
                  (?:           # Begin {(special normal*)*} construct
                    <           # or a < starting a non-blacklist tag.
                    (?!/?(?:textarea|pre|script)\b)
                    [^<]*+      # more non-"<" {normal*}
                  )*+           # Finish "unrolling-the-loop"
                  (?:           # Begin alternation group.
                    <           # Either a blacklist start tag.
                    (?>textarea|pre|script)\b
                  | \z          # or end of file.
                  )             # End alternation group.
                )  # If we made it here, we are not in a blacklist tag.
                %Six';

          $options = [
            'max-age'  => 300,
            's-maxage' => 900
          ];
          $response->setCache($options);

          $new_buffer = preg_replace($re, " ", $response->getBody());
          $response->setBody($new_buffer);
        }
      }
    }
  }
}
