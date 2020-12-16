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
let last = 0, interval_num, resultZeroTime = 0;
function GetTemplate(id, title, score)
{
    return $('<tr>')
    .append(
        $('<td>')
        .append(
            title
        )
        .click(function(){ location.href = "/article.php?id=" + id; })
    )
    .append(
        $('<td>')
        .append(
            score
        )
    );
    // <tr onclick="window.open('https://google.com');">
    //     <td>ekwdnkleasnmddewdewdwedwedwedwedweklasnmkldjasldjskladjklasjdklasjdiklsa</td>
    //     <td>0.0005</td>
    // </tr>
}

function IntervalGetSearchResult()
{
    let s = queryString();
    s.last = last;
    $.get("/api/search.php", s, function (data) {
        let result = JSON.parse(data);
        console.log(result);
        result.forEach(element => {
            if (element["SearchResultRate"] < 0) {
                $('#negative').prepend(GetTemplate(element["SearchResultId"], element["Title"], element["SearchResultRate"]))
            }
            else if (element["SearchResultRate"] > 0) {
                $('#positive').prepend(GetTemplate(element["SearchResultId"], element["Title"], element["SearchResultRate"]))
            }
            last = element["SearchResultId"];
        });
        if (result.length > 0) {
            resultZeroTime = 0;
        }
        else {
            resultZeroTime++;
            console.log(resultZeroTime);
        }
    });
    if (resultZeroTime > 20){
        clearInterval(interval_num);
        $("#status")[0].innerHTML = "載入完成！";
        if (last == 0) {
            $("#status")[0].innerHTML = "載入完成！沒有任何結果！";
        }
    }
}

IntervalGetSearchResult();
interval_num = setInterval(IntervalGetSearchResult, 1500);
