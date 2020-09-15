@component('mail::message')
# Welcome To My Website {{ $user->name }}, Here is Your Action Details<br>
<p>You have Succeesfully {{ $action }} Book</p><br>

# Book Details

@component('mail::table')
|   Book ID     |       Book Name    |       Book Description    |      Book Author     |
| ------------- |:------------------:|:-------------------------:|:--------------------:|
| {{$book->id}} |{{$book->book_name}}|{{$book->book_description}}|{{$book->book_author}}|
@endcomponent

@component('mail::button', ['url' => 'localhost:8000/#/admin', 'color' => 'success'])
 Go Back
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
