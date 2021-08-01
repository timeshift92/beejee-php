<?php

namespace Framework\Http\Session;

class SessionFlash
{
    public static function success($message)
    {
        $_SESSION['alert'] = ['type' => 'success', "message" => $message];
    }

    public static function error($message)
    {
        $_SESSION['alert'] = ['type' => 'error', "message" => $message];
    }


    public function getMessage()
    {
        if (isset($_SESSION['alert'])) {
            $type = $_SESSION['alert']['type'] == 'error' ? 'bg-danger' : "bg-success";
            $typeMessage = $_SESSION['alert']['type'] == 'error' ? 'Ошибка' : "Успешно";
            $message = $_SESSION['alert']['message'];
            unset($_SESSION['alert']);
            return <<<EOD
  <div aria-live="polite" aria-atomic="true" >
  <div class="toast $type toast-top"   data-delay="10000">
  
    <div class="toast-header">
    <strong class="mr-auto">$typeMessage</strong>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body">
      $message
    </div>
  </div>
</div>  
<script >setTimeout(()=> $('.toast').toast('show'), 200) </script>   

EOD;
        }
        return '';

    }
}