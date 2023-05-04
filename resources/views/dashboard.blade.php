<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('chat/css/style.css') }}">
    <title>Chat</title>
</head>
<style>
</style>

<body>
    <div class="container">
        <div class="row container-of-app">
            <div style="min-height:700px;" class="col-lg-3 p-0">
                <div style="min-height:100%;" class="card">
                    <div class="card-header box-info-account">
                        {{ auth()->user()->name }}
                    </div>
                    <nav class="navbar navbar-light bg-light">
                        <form style="width:100%;" class="form-inline">
                            <input style="width:100%;" class="form-control" type="search" id="input-search-fast-users" placeholder="Nhập email, tên tài khoản...">
                        </form>
                    </nav>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="home-tab" data-toggle="tab" data-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true"><i class="far fa-comment"></i></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-tab" data-toggle="tab" data-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false"><i class="fas fa-user-friends"></i></button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-toggle="tab" data-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false">Con</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">...</div>
                            <div class="tab-pane fade show active box-my-friends" id="profile" role="tabpanel" aria-labelledby="profile-tab">






                            </div>
                            <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">...</div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="min-height:700px;" class="col-lg-9">
                <div class="row">
                    <div style="height: 620px;" class="col-lg-12 p-0">
                        <div style="height: 100%;" class="card">
                            <div data-id="" class="card-header info-friend-chat-with-me">

                            </div>
                            <div class="card-body box-messages">
                                <!-- <div class="row">
                                    <div class="col-lg-12">
                                        <div style="float: right;" class="alert alert-success box-content-message-chat" role="alert">
                                            hlloe
                                        </div>
                                    </div>
                                </div>  -->
                            </div>
                        </div>
                    </div>
                    <div style="display:none;" class="col-lg-12 p-4 container-form-send-message">
                        <form class="form-inline" id="form-submit-send-messages">
                            <input autocomplete="off" require style="width:84%;" id="input_send_messages" name="message" class="form-control mr-sm-2" type="text" placeholder="Nhập tin nhắn">
                            <button style="width:14%;" class="btn btn-outline-success my-2 my-sm-0 btn-send-message" type="button"><i class="	fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/js/app.js"></script>
    <script>
        const userID = '{{ auth()->user()->id }}'
        const userName = '{{ auth()->user()->name }}'
    </script>
    <script src="{{ asset('chat/js/chat.js') }}"></script>
</body>

</html>