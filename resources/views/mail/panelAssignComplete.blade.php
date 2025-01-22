<x-mail::message>
    

@if($status === "success")
# Panel Assignment Complete

The panel assignment process has been successfully completed. Thank you for your patience.
@elseif($status === "fail")
# An error occured

{{ $message }}
@else
# No
@endif

</x-mail::message>


