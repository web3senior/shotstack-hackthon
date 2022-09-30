<?php

class Buffer
{
  function __construct()
  {
  }

  function start()
  {
    /* start output buffering at the top of our script with this simple command */
    /* we've added "ob_postprocess" (our custom post processing function) as a parameter of ob_start */
    ob_start('bufferFilter');
  }

  function end()
  {
    /* end output buffering and send our HTML to the browser as a whole */
    ob_end_flush();
  }
}
