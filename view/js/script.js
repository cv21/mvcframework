/**
 * Created by JetBrains PhpStorm.
 * User: Vitaly
 * Date: 27.04.12
 * Time: 17:32
 * To change this template use File | Settings | File Templates.
 */

function isValidEmail (email, strict)
{
    if ( !strict ) email = email.replace(/^\s+|\s+$/g, '');
    return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}

// оставляет в массиве только уникальные значения

function unique(array)
{
    array.sort();
    //alert('before ' + array);
    for(var i = 0; i < array.length; i++)
    {
        if(array[i] == -1) continue;
        for(var j = 0; j < array.length; j++)
        {
            //alert('array[' + i + '] = ' + array[i] + 'array[' + j +'] = ' + array [j]);
            if((array[i] === array[j]) && (i !== j))
            {
                //alert('array['+ j + '] = '+ array[j] + ' - deleted');
                //array.splice(j-1,1);
                array[j] = null;
                //alert('condition after [' + array + ']');
            }
        }
    }
    var uniqueArray = new Array();
    for(var i = 0; i < array.length; i++)
    {
        if(array[i] != null)
        {
            uniqueArray.push(array[i]);
        }
    }
    //alert('after ' + uniqueArray);
    return uniqueArray;
}

// осуществляет формирование массива пользователей, которые отображаются на странице

function getUsers() //выдает массив id'шников всех юзеров замеченных на странице
{
    var sups = $('.message').find('sup');
    var authors = new Array();
    for(var i = 0; i < sups.length; i++)
    {
        authors[i] = sups.next('input[type=hidden]').slice(i).val();
    }
    return unique(authors);
}

// изменяет рейтинги у соответствующих юзеров
// на входе массив с id юзеров и массив с их рейтингами соответственно

function setRatings(users, ratings)
{
    for(var i = 0; i < users.length; i++)
    {
        var allInputs = $('.message').find('sup').next('input[type=hidden]').sort();
        $.each(allInputs, function(){
            if($(this).val() == users[i])
            {
                $(this).prev('sup').html(ratings[i]);
            }
        })
    }
}

// осуществляет асинхронный ajax запрос
// onComplete - ф-ция вызываемая по завершению запроса

function ajaxSend(url, data, onComplete, async) //посылает ajax-запрос
{
    $.ajax({
        async: async,
        type: "POST",
        url: url,
        data: data
    }).complete(onComplete);
}

// выполняет сериализацию численного массива в
// строку для передачи в php как массив с именем "name"

function serializeArray(array, name)
{
    var arrLength = array.length;
    for(var i = 0; i < arrLength; i++)
    {
        array[i] = '' + name + '[]=' + array[i];
    }
    return array.join('&');
}


