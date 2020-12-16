function queryVerify(q) {
    return q !== "";
}

function emailVerify(email)
{
    var emailPattern = /^(?!\.)[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    return emailPattern.test(email);
}

function send()
{
    let query = document.getElementById("q").value;
    let email = document.getElementById("e").value;
    query = query.trim();
    if (!queryVerify(query)) {
        Swal.fire({
            icon: 'error',
            title: '查詢句不得為空白',
            confirmButtonText: '確定'
        });
        return;
    }
    else if (!emailVerify(email)) {
        Swal.fire({
            icon: 'error',
            title: 'Email 格式錯誤',
            confirmButtonText: '確定'
        });
        return;
    }
    else {
        var formElement = document.getElementById("myForm");
        const data = new URLSearchParams(new FormData(formElement));
        console.log(data);
        Swal.fire({
            icon: 'info',
            title: '確認你的搜尋句跟 Email 是否正確？',
            confirmButtonText: '確定',
            cancelButtonText: '取消',
            showCancelButton: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return fetch(`/api/create_search.php`, {method: 'POST', body: data})
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText)
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            console.log(result);
            if (result.isConfirmed) {
                if (result.value.ok === 1){
                    Swal.fire({
                        icon: 'success',
                        title: '成功！',
                        text: '我們已收到您的查詢'
                    })
                }
                else if (result.value.ok === 0){
                    Swal.fire({
                        icon: 'error',
                        title: '失敗',
                        text: `${result.value.msg}`
                    })
                }
            }
        })
    }
}