function queryString ()
{
    var query_string = {};
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++)
    {
        var pair = vars[i].split("=");
        // If first entry with this name
        if (typeof query_string[pair[0]] === "undefined")
        {
            query_string[pair[0]] = pair[1];
            // If second entry with this name
        } else if (typeof query_string[pair[0]] === "string")
        {
            var arr = [query_string[pair[0]], pair[1]];
            query_string[pair[0]] = arr;
            // If third or later entry with this name
        } else
        {
            query_string[pair[0]].push(pair[1]);
        }
    }
    return query_string;
}
let arr_sent = [{"label": "green", "face": "smile"}, {"label": "red", "face": "frown"}]
function GetTemplate(score, sentence)
{
    let index = (score < 0.0) ? 1 : 0;
    return $('<div>', {class: 'ui raised segment'})
    .append(
        $('<a>', {class: 'ui ribbon label'})
        .addClass(arr_sent[index]["label"])
        .append(
            $('<i>', {class: 'large outline icon'})
            .addClass(arr_sent[index]["face"])
        )
    )
    .append(
        $('<div>', {class: 'ui label'})
        .append(
            $('<i>', {class: 'glasses icon'})
        )
        .append("系統選擇")
    )
    .append(
        $('<p>')
        .append(
            sentence
        )
    );
    // 
    // <div class="ui raised segment">
    //     <a class="ui green ribbon label"><i class="large smile outline icon"></i></a>
    //     <div class="ui label">
    //         <i class="glasses icon"></i>
    //         系統選擇
    //     </div>
    //     <p>
    //         」照顧弱勢群體有助遏止疫情擴散從以上論述得知，僅憑藉大規模接種疫苗，恐怕很難達成群體免疫的目標。不過，若是轉換角度，從照顧社區裡抵抗力較弱的群體下手，或許可以有效遏止疫情擴散。英國愛丁堡大學免疫學與傳染病教授萊利(EleanorRiley)表示：「我們可以改變群體免疫的預定目標，使用有限數量的疫苗，保護社區裡最需要的人，不必擔心群體裡抵抗力較強、能與病毒和平共存的成員。
    //     </p>
    // </div>
}

function GetArticle()
{
    let s = queryString();
    jQuery.get("/api/article.php", s, function (data) {
        $('#loading_screen').remove();
        let result = JSON.parse(data);
        $('#title')[0].innerText = result["title"];
        $('#url')[0].href = result["url"];
        let score_face = '<i class="smile outline icon"></i>';
        if (result["score"] < 0) {
            score_face = '<i class="frown outline icon"></i>';
        }
        $('#total_sent')[0].innerHTML = score_face;
        const sentences = result["sentences"];
        sentences.forEach(element => {
            $('#article')[0].append(GetTemplate(element["sentence_grade"], element["sentences"])[0]);
        });
        
    });
}

GetArticle();

