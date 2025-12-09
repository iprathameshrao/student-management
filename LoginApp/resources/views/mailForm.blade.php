<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Page Title</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
   <style>
            .grid-container {
                width: 300px; /* Define a width for the form */
                margin: 0 auto; 
            }
   </style>
</head>
<body>
    
    
    <div class="grid-container">
        <h1>This is gmail app</h1>
        
        @if(session('success'))
        <div>{{ session('success') }}</div>
        @endif
        
        <form action="send-mail" method="POST">
            @csrf
            
            <label>Recipients</label>
            <input type="email" name="to" placeholder="write mail address" required value="{{ old('to') }}">
            <br/><br/>
            
            <label>Subject</label>
            <input type="text" name="subject" placeholder="enter subject">
            <br/><br/>
            
            <label>Message</label>
            <textarea name="msg" placeholder="type your message" ></textarea>
            <br/><br/>
            
            <button type="submit">Send Email</button>
        </form>
    </div>

</body>
</html>
