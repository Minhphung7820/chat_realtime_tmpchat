let userFriends = [];
let userActiveArray = [];
var boxMessages = document.querySelector('.box-messages');
// ===============================================================================
const timeNowFormat = () => {
        const date = new Date();
        return `${date.getFullYear()}-${(date.getMonth() + 1).toString().padStart(2, '0')}-${date.getDate().toString().padStart(2, '0')} ${date.getHours().toString().padStart(2, '0')}:${date.getMinutes().toString().padStart(2, '0')}:${date.getSeconds().toString().padStart(2, '0')}`;
    }
    // =================================================================================
const debounce = (fn, delay) => {
    let timeoutId;
    return function(...args) {
        if (timeoutId) {
            clearTimeout(timeoutId);
        }
        timeoutId = setTimeout(() => {
            fn(...args);
        }, delay);
    };
};
// ===================================================================================
// const searchFastAccounts = (searchTerm) => {
//     console.log(`Đang tìm kiếm với từ khóa "${searchTerm}"...`);
//     axios.get("/api/ajax/search-fast-account/" + searchTerm + "/" + userID)
//         .then(response => {
//             if (response.data.success == true) {
//                 if (response.data.message.length == 0) {
//                     console.log("Không tìm thấy kết quả !");
//                 } else {
//                     let arrayResults = response.data.message;
//                     arrayResults.forEach(value => {
//                         console.log(value.first_name + " " + value.last_name);
//                     });
//                 }
//             }
//         })
//         .catch(error => {

//         })
// };
// ====================================================================================
// const delayedSearchFastAccount = debounce((event) => {
//     const searchTerm = event.target.value.trim();
//     if (searchTerm.length >= 3) {
//         searchFastAccounts(searchTerm);
//     }
// }, 300);

// document.getElementById("inputSearchFastAccount").addEventListener("keyup", delayedSearchFastAccount)
// ===============================================================================
const lastUptimeUpdateRealTime = () => {
    userActiveArray.map(u => {
        if (parseInt(u.online) === 0) {
            u.status = getTimeDiff(new Date(u.last_active));
        } else {
            u.status = "Đang hoạt động";
        }
    })
    const allDotStatusActive = document.querySelectorAll(`.dot-status-active`);
    allDotStatusActive.forEach(dot => {
        var idSpanDot = parseInt(dot.dataset.id);
        userActiveArray.forEach(user => {
            if (parseInt(idSpanDot) === parseInt(user.user_id)) {
                if (parseInt(user.online) === 1) {
                    document.querySelector(`.dot-status-active-user-${parseInt(user.user_id)}  i`).style.display = 'block'
                    document.querySelector(`.status-active-of-user-${parseInt(user.user_id)}`).innerHTML = user.status;

                    if (document.querySelector(`.status-active-of-user-chatting-${parseInt(user.user_id)}`)) document.querySelector(`.status-active-of-user-chatting-${parseInt(user.user_id)}`).innerHTML = user.status;
                    if (document.querySelector(`.dot-status-active-user-chatting-${parseInt(user.user_id)}`)) document.querySelector(`.dot-status-active-user-chatting-${parseInt(user.user_id)} i`).style.display = 'block';
                } else {
                    document.querySelector(`.dot-status-active-user-${parseInt(user.user_id)}  i`).style.display = 'none'
                    document.querySelector(`.status-active-of-user-${parseInt(user.user_id)}`).innerHTML = user.status;

                    if (document.querySelector(`.status-active-of-user-chatting-${parseInt(user.user_id)}`)) document.querySelector(`.status-active-of-user-chatting-${parseInt(user.user_id)}`).innerHTML = user.status;
                    if (document.querySelector(`.dot-status-active-user-chatting-${parseInt(user.user_id)}`)) document.querySelector(`.dot-status-active-user-chatting-${parseInt(user.user_id)} i`).style.display = 'none';
                }
            }
        })
    })
};
// ===============================================================================
const displayStatusActive = (id, last, online) => {
        userActiveArray.map(u => {
            if (parseInt(u.user_id) === parseInt(id)) {
                if (parseInt(online) === 0) {
                    u.last_active = last;
                    u.status = getTimeDiff(new Date(u.last_active));
                    u.online = 0;
                } else {
                    u.last_active = last;
                    u.status = "Đang hoạt động";
                    u.online = 1;
                }
            }
        })
    }
    // =================================================================================
const scrollToBottom = () => {
        const scrollTop = boxMessages.scrollHeight - boxMessages.clientHeight;
        boxMessages.scrollTo({
            top: scrollTop,
            behavior: 'instant'
        });
    }
    // =================================================================================
const chatWithFriends = (user) => {
        if (document.querySelector(".info-friend-chat-with-me").dataset.id == parseInt(user.id)) return false;
        var message = '';
        Promise.all([
                axios.post(`/load-detail-conversation`, {
                    user_id: parseInt(userID),
                    friend_id: parseInt(user.id),
                    type: "conversation"
                }, {
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    }
                }),
                Promise.resolve(user)
            ])
            .then(([response, user]) => {
                    // console.log(parseInt(response.data.conversation_id));
                    document.querySelector(`.container-form-send-message`).style.display = 'block';
                    document.querySelector(`#receiver_id`).value = parseInt(user.id);
                    document.querySelector(`#conversation_id`).value = parseInt(response.data.conversation_id);
                    if (response.data.result.length === 0) {
                        message += ` <div class="row box-no-message">
                                        <div class="col-lg-12">
                                            <div style="width:100%;" class="alert alert-warning text-center" role="alert">
                                                Hãy cùng trò chuyện nào !
                                            </div>
                                        </div>
                                    </div>`;
                    } else {
                        response.data.result.forEach(e => {
                            var testRight = (e.user_id === parseInt(userID)) ? "float: right;" : "";
                            var colorMessage = (e.user_id === parseInt(userID)) ? "success" : "primary";
                            message += `           
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div style="${testRight}" class="alert alert-${colorMessage} box-content-message-chat" role="alert">
                                                ${e.message}
                                            </div>
                                        </div>
                                    </div>`;
                        });
                    }

                    var statusUserChatting = userActiveArray.find(u => parseInt(u.user_id) === parseInt(user.id))
                    document.querySelector(".info-friend-chat-with-me").innerHTML = ` 
                    <div class="row p-0">
                                                    <div class="col-lg-1">
                                                       <div class="container-image-avatar">
                                                            <div>${user.name.charAt(0)}</div>
                                                            <span data-id="${user.id}"  class="dot-status-active-user-chatting-${user.id}"><i ${parseInt(statusUserChatting.online) === 1 ? `style="display:block;"` : ``} class="	fas fa-circle dot-when-online"></i></span>
                                                       </div>
                                                    </div>
                                                    <div class="col-lg-11">
                                                        <div class="h5">${user.name}</div>
                                                        <p class="status-active-of-user-chatting-${user.id}">${statusUserChatting.status}</p>
                                                    </div>
                                                </div>`;

            document.querySelector(".info-friend-chat-with-me").dataset.id = user.id;
            message += `<div class="row container-typing-amination container-typing-amination-of-conversation-${parseInt(response.data.conversation_id)}">
                    <div class="col-lg-12">
                        <div class="alert alert-primary box-content-message-chat" role="alert">
                             <div class="typing-animation">
                                 <div class="dot"></div>
                                 <div class="dot"></div>
                                 <div class="dot"></div>
                             </div>
                        </div>
                    </div>
                </div>`;
            boxMessages.innerHTML = message;
            boxMessages.scrollTop = boxMessages.scrollHeight;
            Echo.private(`typing.${parseInt(response.data.conversation_id)}`)
                .listenForWhisper(`typing.${parseInt(response.data.conversation_id)}`, (e) => {
                    if (document.querySelector(`.container-typing-amination-of-conversation-${parseInt(response.data.conversation_id)}`)) {
                        document.querySelector(`.container-typing-amination-of-conversation-${parseInt(response.data.conversation_id)}`).style.display = 'block';
                    }
                    scrollToBottom();
                })
            Echo.private(`stopTyping.${parseInt(response.data.conversation_id)}`)
                .listenForWhisper(`stopTyping.${parseInt(response.data.conversation_id)}`, (e) => {
                    if (document.querySelector(`.container-typing-amination-of-conversation-${parseInt(response.data.conversation_id)}`)) {
                        document.querySelector(`.container-typing-amination-of-conversation-${parseInt(response.data.conversation_id)}`).style.display = 'none';
                    }
                })
            Echo.private(`seen.${parseInt(response.data.conversation_id)}`)
                .listenForWhisper(`seen.${parseInt(response.data.conversation_id)}`, (e) => {
                    console.log(`${e.seenerName.split(" ").pop()} đã xem lúc ${e.time}`);
                })
            document.querySelector(`.btn-send-message`).addEventListener('click', function (e) {
                e.preventDefault();
                if (document.querySelector(`#input_send_messages`).value.length === 0) return
                handleStopTyping();
                sendMessages(userID, response.data.conversation_id, document.querySelector(`#input_send_messages`).value);
            })
            document.querySelector(`#input_send_messages`).addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (e.target.value.length === 0) return
                    handleStopTyping();
                    sendMessages(userID, response.data.conversation_id, e.target.value);
                }
            });

            Echo.private(`send.${parseInt(response.data.conversation_id)}`)
                .listenForWhisper(`send.${parseInt(response.data.conversation_id)}`, (e) => {
                    let messageNewSend = document.createElement('div')
                    messageNewSend.classList.add(`row`)
                    messageNewSend.innerHTML = ` 
                             <div class="col-lg-12">
                                <div class="alert alert-primary box-content-message-chat" role="alert">
                                    ${e.message}
                                </div>
                             </div>
                       `;
                    boxMessages.insertBefore(messageNewSend, document.querySelector(`.container-typing-amination`));
                    if (document.querySelector(`.box-no-message`)) {
                        document.querySelector(`.box-no-message`).style.display = 'none';
                    }
                    scrollToBottom();
                })
        })
        .catch(error => {
            console.log(error);
        });
}
// ============================================================================
const handleSendMessageToMe = (message) => {
    return new Promise((resolve, reject) => {
        handleStopTyping();
        document.querySelector(`#input_send_messages`).value = ""
        let messageNewSend = document.createElement('div')
        messageNewSend.classList.add(`row`)
        messageNewSend.innerHTML = ` 
                 <div class="col-lg-12">
                    <div style="float: right;" class="alert alert-success box-content-message-chat" role="alert">
                       ${message}
                    </div>
                 </div>
           `;
        boxMessages.insertBefore(messageNewSend, document.querySelector(`.container-typing-amination`));
        if (document.querySelector(`.box-no-message`)) {
            document.querySelector(`.box-no-message`).style.display = 'none';
        }
        scrollToBottom();
        resolve();
    })
}
// ============================================================================
const handleSendMessageToOthers = (conversation, message) => {
    return new Promise((resolve, reject) => {
        Echo.private(`send.${parseInt(conversation)}`)
            .whisper(`send.${parseInt(conversation)}`, {
                message: message
            })
        resolve();
    })
}
// ============================================================================
const handleSendMessageToServer = (sender, conversation, message) => {
    return new Promise((resolve, reject) => {
        let dataSendMessage = {
            sender: parseInt(sender),
            message: message,
            conversation: parseInt(conversation)
        };
        axios.post('/send-message', dataSendMessage, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
            .then(response => {
                resolve(response);
            })
            .catch(error => {
                reject(error)
            })
    })
}
// ============================================================================
let delaySend = true;
const sendMessages = (sender, conversation, message) => {
    if (delaySend == false) return;
    delaySend = false;
    Promise.all([handleSendMessageToMe(message), handleSendMessageToOthers(conversation, message),handleSendMessageToServer(sender, conversation, message)]).then(() => {
        setTimeout(() => {
            delaySend = true;
        }, 300);
    });

}
// ============================================================================
const isInView = (element) => {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}
// ============================================================================
const seenMessage = (conversation, seenerName) => {
    Echo.private(`seen.${parseInt(conversation)}`)
        .whisper(`seen.${parseInt(conversation)}`, {
            seenerName: seenerName,
            conversation: parseInt(conversation),
            time: formatDate(new Date()),
        })
}
// ============================================================================
const isOnlineArrays = (here, friend, id) => {
    let sameUsers = [];

    for (let i = 0; i < here.length; i++) {
        for (let j = 0; j < friend.length; j++) {
            if (here[i].id === friend[j].id) {
                sameUsers.push(here[i]);
                break;
            }
        }
    }
    return sameUsers.some(user => parseInt(user.id) === parseInt(id));

}
// ===============================================================================
const getTimeDiff = (lastActive) => {
    const currentTime = new Date();
    const lastActiveTime = new Date(lastActive);
    const diffInSecs = Math.floor((currentTime - lastActiveTime) / 1000);
    const diffInMins = Math.floor(diffInSecs / 60);
    const diffInHours = Math.floor(diffInMins / 60);
    const diffInDays = Math.floor(diffInHours / 24);

    if (diffInDays > 3) {
        return formatDate(lastActive);
    } else if (diffInDays > 0) {
        return `Online ${diffInDays} ngày trước`;
    } else if (diffInHours > 0) {
        return `Online ${diffInHours} giờ trước`;
    } else if (diffInMins > 0) {
        return `Online ${diffInMins} phút trước`;
    } else {
        return `Vừa mới online`;
    }
}

const formatDate = (date) => {
    const formatter = new Intl.DateTimeFormat('vi-VN', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hour12: false
    });

    return formatter.format(date);
}
// ===============================================================================
const updateLastActive = (id, last) => {
    axios.post(`/update-active`, {
        user_id: parseInt(id),
        last_active: last,
    }, {
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        }
    }).then(response => {

    }).catch(error => {
        // console.log(error);
    })
}
// ===============================================================================
const getFriends = () => {
    return new Promise((resolve, reject) => {
        axios.get(`/get-friends`)
            .then(response => {
                resolve(response);
            })
            .catch(error => {
                reject(error)
            })
    });
}
// ===============================================================================
getFriends()
    .then(response => {
        Echo.join(`chat`)
            .here(users => {
                let myFriendBox = document.querySelector('.box-my-friends');
                response.data.forEach(user => {
                    let friendCard = document.createElement('div');
                    let statusOnlineFriend = isOnlineArrays(users, response.data, parseInt(user.id)) ? "Đang hoạt động" : getTimeDiff(new Date(user.last_active));
                    userActiveArray.push({
                        user_id: parseInt(user.id),
                        last_active: user.last_active,
                        online: isOnlineArrays(users, response.data, parseInt(user.id)) ? 1 : 0,
                        status: isOnlineArrays(users, response.data, parseInt(user.id)) ? "Đang hoạt động" : getTimeDiff(new Date(user.last_active)),
                    })
                    friendCard.dataset.id = parseInt(user.id);
                    friendCard.className = 'card mt-2 mb-2 my-friend box-friend-id-' + parseInt(user.id);
                    friendCard.innerHTML = `
                                    <div class="card-body p-2">
                                        <div class="row p-0">
                                            <div class="col-lg-3">
                                               <div class="container-image-avatar">
                                                    <div>${user.name.charAt(0)}</div>
                                                    <span data-id="${parseInt(user.id)}"  class="dot-status-active dot-status-active-user-${parseInt(user.id)}"><i class="	fas fa-circle dot-when-online"></i></span>
                                               </div>
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="h6">${user.name}</div>
                                                <p class="name-status-active status-active-of-user-${parseInt(user.id)}" i>${statusOnlineFriend}</p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                    myFriendBox.append(friendCard);
                    let flagChatWith = true;
                    document.querySelector(".box-friend-id-" + parseInt(user.id)).addEventListener("click", function (e) {
                        e.preventDefault();
                        if (flagChatWith == false) return;
                        flagChatWith = false
                        chatWithFriends(user);
                        setTimeout(() => {
                            flagChatWith = true;
                        }, 400);
                    });
                });
            })

        Echo.join('chat')
            .joining(user => {
                // console.log(user.name + " đã tham gia");
                const isFriend = response.data.some(friend => parseInt(friend.id) === parseInt(user.id));
                if (isFriend) {
                    displayStatusActive(parseInt(user.id), timeNowFormat(), 1)
                }

            })
        Echo.join('chat')
            .leaving(user => {
                // console.log(user.name + " đã rời");
                var containerBoxFriendToLeave = document.querySelector(".box-my-friends");
                var friendLeave = document.querySelector(".box-friend-id-" + parseInt(user.id));
                if (friendLeave) {
                    let delayUpdateActive;
                    if (delayUpdateActive == false) return;
                    delayUpdateActive = false;
                    updateLastActive(parseInt(user.id), timeNowFormat());
                    displayStatusActive(parseInt(user.id), timeNowFormat(), 0)
                    setTimeout(() => {
                        delayUpdateActive = true;
                    }, 500);
                }
            })

    }).catch(error => {
        console.log(error);
    })

setInterval(() => {
    lastUptimeUpdateRealTime();
}, 500);

// =================================================================================
const handleTyping = () => {
    Echo.private(`typing.${parseInt(document.querySelector(`#conversation_id`).value)}`)
        .whisper(`typing.${parseInt(document.querySelector(`#conversation_id`).value)}`, {
            conversation: parseInt(document.querySelector(`#conversation_id`).value),
            name: userName,
            typing: true
        });
    // console.log(parseInt(document.querySelector(`#conversation_id`).value));
}

const handleStopTyping = () => {
    Echo.private(`stopTyping.${parseInt(document.querySelector(`#conversation_id`).value)}`)
        .whisper(`stopTyping.${parseInt(document.querySelector(`#conversation_id`).value)}`, {
            conversation: parseInt(document.querySelector(`#conversation_id`).value),
            name: userName,
            typing: true
        });
    // console.log(parseInt(document.querySelector(`#conversation_id`).value));
}

document.querySelector(`#input_send_messages`).addEventListener('input', function (e) {
    e.preventDefault();
    handleTyping();
    if (e.target.value.length == 0) {
        handleStopTyping();
    }
})
document.querySelector(`#input_send_messages`).addEventListener('blur', handleStopTyping);

document.querySelector(`#input_send_messages`).addEventListener('focus', function () {
    seenMessage(parseInt(document.querySelector(`#conversation_id`).value), userName);
})





let users = [{
    id: 1,
    name: "TMP",
    price: 7000,
    quaty: 20,
    total: ""

},
{
    id: 2,
    name: "ABC",
    price: 8000,
    quaty: 10,
    total: ""

},
{
    id: 3,
    name: "CSS",
    price: 7000,
    quaty: 4,
    total: ""

},
]