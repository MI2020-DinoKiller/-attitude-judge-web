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
// 閉包含數
(function() {
  /**
   * Decimal adjustment of a number.
   *
   * @param {String}  type  The type of adjustment.
   * @param {Number}  value The number.
   * @param {Integer} exp   The exponent (the 10 logarithm of the adjustment base).
   * @returns {Number} The adjusted value.
   */
  function decimalAdjust(type, value, exp) {
    // If the exp is undefined or zero...
    if (typeof exp === 'undefined' || +exp === 0) {
      return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // If the value is not a number or the exp is not an integer...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
      return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
  }

  // Decimal round
  if (!Math.round10) {
    Math.round10 = function(value, exp) {
      return decimalAdjust('round', value, exp);
    };
  }
  // Decimal floor
  if (!Math.floor10) {
    Math.floor10 = function(value, exp) {
      return decimalAdjust('floor', value, exp);
    };
  }
  // Decimal ceil
  if (!Math.ceil10) {
    Math.ceil10 = function(value, exp) {
      return decimalAdjust('ceil', value, exp);
    };
  }
})();
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
            Math.round10(parseFloat(score), -5)
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
                $('#negative').append(GetTemplate(element["SearchResultId"], element["Title"], element["SearchResultRate"]))
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
    }).then(()=>{
        $("#status")[0].innerHTML = "載入完成！";
        if (last == 0) {
            $("#status")[0].innerHTML = "載入完成！沒有任何結果！";
        }
    });
    
}

IntervalGetSearchResult();
// interval_num = setInterval(IntervalGetSearchResult, 1500);
