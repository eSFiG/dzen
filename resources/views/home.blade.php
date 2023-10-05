<html>
<head>
    <meta charset="utf-8">
    <title>Comments</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css?ver='.rand(0, 10000)) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" >
</head>

<body onload="getCaptcha();">
<div class="content">
<div class="mt-3">
    <div class="title">
        <div class="cell" style="width: 150px" onclick="window.location='/sort/user_name/{{$dir}}?page={{$pagination->currentPage()}}';">User name</div>
        <div class="cell" style="width: 250px" onclick="window.location='/sort/email/{{$dir}}?page={{$pagination->currentPage()}}';">Email</div>
        <div class="cell" style="width: 350px">Text</div>
        <div class="cell" style="width: 200px" onclick="window.location='/sort/created_at/{{$dir}}?page={{$pagination->currentPage()}}';">Created at</div>
        <div class="cell" style="width: 74px"></div>
    </div>
    <div id="comments">
    @foreach($comments as $comment)
        <div id="{{$comment->id}}" class="body">
            <div class="cell" style="width: 150px">{{ $comment->user_name }}</div>
            <div class="cell" style="width: 250px">{{ $comment->email }}</div>
            <div class="cell" style="width: 350px">{{ $comment->text }}</div>
            <div class="cell" style="width: 200px">{{ $comment->created_at }}</div>
            <div class="cell"><input type="image" src="{{ asset('/reply.png') }}" onclick="reply({{ $comment->id }}); location.hash = ' '; location.hash = '#comment_form'"></div>
            <div class="cell"><input type="image" src="{{ asset('/chat.png') }}" onclick="window.location='/replies/{{ $comment->id }}/{{$pagination->currentPage()}}';"></div>
        </div>
        @php($i=1)
        @foreach($comment->replies as $reply)
            @if($i > 3)
                @break
            @endif
            <div id="comments" style="margin-left: 30px">
                <div class="body">
                    <div class="reply" style="width: 150px">{{ $reply->user_name }}</div>
                    <div class="reply" style="width: 250px">{{ $reply->email }}</div>
                    <div class="reply" style="width: 350px">{{ $reply->text }}</div>
                    <div class="reply" style="width: 255px">{{ $reply->created_at }}</div>
                </div>
            </div>
            @php($i++)
        @endforeach
    @endforeach
    </div>
</div>
<div class="mt-3">
    {{ $pagination->links() }}
</div>

<form id="comment_form" name="comment_form" onsubmit="event.preventDefault(); validate();">
    @csrf
    <div class="for_form">
    <p class="for_form">Enter user:</p>
    <input class="form" id="user_name" type="text" name="user_name" placeholder="User">
    <div class="error" id="user_val" hidden>Please, type in user</div>

    <p class="for_form">Enter email:</p>
    <input class="form" id="email" type="text" name="email" placeholder="email@example.com">
    <div class="error" id="email_val" hidden>Please, type in email</div>
    <div class="error" id="email_form" hidden>Please, type in correct format of email</div>

    <p class="for_form">Enter home page:</p>
    <input class="form" id="parent_id" type="text" name="parent_id" placeholder="Home page">

    <p class="for_form">Enter comment:</p>
    <textarea class="form" id="text" name="text" placeholder="Comment"></textarea>
    <div class="error" id="text_val" hidden>Please, type in comment</div>

    <img id="captcha_img" class="m-2"></img>
    <input class="m-1" type="button" name="refresh" value="Resfresh" onclick="getCaptcha()">
    <input type="text" id="captcha" name="captcha">
    <div class="error" id="captcha_val" hidden>Please, type in captcha</div>
    <div class="error" id="captcha_err" hidden>Please, try again</div>

    <input id="add_comment" type="submit" value="Add comment">
    </div>
</form>
</div>
</body>
</html>

<script src="{{ asset('/js/jquery-3.7.1.js')}}" type="text/javascript"></script>
<script type="text/javascript">
    let key, img;
    function getCaptcha() {
        $('#captcha').val(' ');
        $.get('http://dzen.oneb.pro/captcha/api/math').done(function (response) {
            key = response.key;
            img = "data:image/png;base64," + response.img;
            $("#captcha_img").attr('src', response.img);
        });
    }

    function reply(id, edit = true) {
        $('#parent_id').val(id);
        if (!edit) {
            $('#parent_id').prop('disabled', true);
        }
    }

    function validate() {
        $('#user_name').change(function () {
            $('#user_val').hide();
        });

        $('#email').change(function () {
            $('#email_val').hide();
            $('#email_form').hide();
        });

        $('#text').change(function () {
            $('#text_val').hide();
        });

        $('#captcha').change(function () {
            $('#captcha_val').hide();
            $('#captcha_err').hide();
        });

        const form = document.comment_form;
        let validity = true;

        if (form.user_name.value === "") {
            document.getElementById('user_val').hidden = false;
            validity = false;
        }
        if (form.email.value === "") {
            document.getElementById('email_val').hidden = false;
            validity = false;
        } else {
            document.getElementById('email_val').hidden = true;
            let email = form.email.value;
            atpos = email.indexOf("@");
            dotpos = email.lastIndexOf(".");
            if (atpos < 1 || ( dotpos - atpos < 2 )) {
                document.getElementById('email_form').hidden = false;
                validity = false;
            }
        }
        if (form.text.value === "") {
            document.getElementById('text_val').hidden = false;
            validity = false;
        }

        if (form.captcha.value === "") {
            document.getElementById('captcha_val').hidden = false;
            validity = false;
        }

        if (validity) {
            let data = {
                "user_name": form.user_name.value,
                "email": form.email.value,
                "parent_id": form.parent_id.value,
                "text": form.text.value,
                "key": key,
                "captcha": form.captcha.value,
            };
            request(data);
        }
    }

    function create(data, name) {
        let body = document.createElement('div'),
            replies = document.createElement('div'),
            user = document.createElement('div'),
            email = document.createElement('div'),
            text = document.createElement('div'),
            time = document.createElement('div'),
            reply = document.createElement('div'),
            reply_button = document.createElement('input'),
            comments = document.createElement('div'),
            comments_button = document.createElement('input');
        replies.setAttribute('style', 'margin-left: 30px')
        body.setAttribute('class', 'body');
        user.setAttribute('class', name);
        user.setAttribute('style', 'width: 150px');
        user.innerHTML = data.user_name;
        body.appendChild(user);

        email.setAttribute('class', name);
        email.setAttribute('style', 'width: 250px');
        email.innerHTML = data.email;
        body.appendChild(email);

        text.setAttribute('class', name);
        text.setAttribute('style', 'width: 350px');
        text.innerHTML = data.text;
        body.appendChild(text);

        time.setAttribute('class', name);
        if (data.parent_id) {
            time.setAttribute('style', 'width: 255px');
        } else {
            time.setAttribute('style', 'width: 200px');
        }
        let date = new Date(data.created_at);
        time.innerHTML =
            date.getFullYear() + "-" +
            ("00" + (date.getMonth() + 1)).slice(-2) + "-" +
            ("00" + date.getDate()).slice(-2) + " " +
            ("00" + date.getHours()).slice(-2) + ":" +
            ("00" + date.getMinutes()).slice(-2) + ":" +
            ("00" + date.getSeconds()).slice(-2);
        body.appendChild(time);
        if (data.parent_id) {
            replies.appendChild(body);
            $('#' + data.parent_id).after(replies);
            location.hash = ' ';
            location.hash = '#' + data.parent_id;
        }
        else {
        reply.setAttribute('class', name);
        reply_button.setAttribute('type', 'image');
        reply_button.setAttribute('src', '{{ asset('/reply.png') }}');
        reply_button.setAttribute('onclick', 'reply(' + data.id + ');');
        reply.appendChild(reply_button);
        body.appendChild(reply);

        comments.setAttribute('class', name);
        comments_button.setAttribute('type', 'image');
        comments_button.setAttribute('src', '{{ asset('/chat.png') }}');
        comments_button.setAttribute('onclick', 'window.location="/replies/' + data.id + '";');
        comments.appendChild(comments_button);
        body.appendChild(comments);
        document.getElementById('comments').appendChild(body);
        }
    }

    function request(data) {
        $.post('http://dzen.oneb.pro/api/save', data).done(function (response) {
            if (response.data.parent_id) {
                if (response.count <= 3) {
                    create(response.data, 'reply');
                }
                $('#text').val(' ');
                getCaptcha();
            }
            else {
                create(response.data, 'cell');
                $('#text').val(' ');
                getCaptcha();
            }
        })
        .fail(function (status) {
            if(status.responseJSON.errors.captcha) {
                document.getElementById('captcha_err').hidden = false;
                getCaptcha();
            }
        });
    }
</script>
