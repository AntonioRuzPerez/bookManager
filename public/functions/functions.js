function ajaxLoadMenuOption(menu,trad){
    let data={};
    data["lang"]=trad;
    $.ajax({
        type: "POST",
        data: data,
        url:  "templates/"+menu+"/formulario"+menu+".php",
        success: function (ret) {
            $("main.content-area").html(ret);
            if(menu==="GetBook"){
                var btn=document.getElementById('searchButton');
                btn.click();
            }
        },
    });
    $("div#formEditBook").html("");
}

function ajaxLoadBodyOption(menu,trad){
    let data={};
    data["lang"]=trad;
    switch (menu) {
        case "AddBook":
            data["isbn"]=   document.getElementById('isbn').value;
            data["title"]=  document.getElementById('title').value;
            data["author"]= document.getElementById('author').value;
            data["year"]=   document.getElementById('year').value;
            data["summary"]=document.getElementById('summary').value;
            break;
        case "GetBook":
            data["isbn"]=document.getElementById('isbn').value;
            data["title"]=document.getElementById('title').value;
            data["author"]=document.getElementById('author').value;
            break;
        case "EditBook":
            data["id"]=     document.getElementById('edit_id').value;
            data["isbn"]=   document.getElementById('edit_isbn').value;
            data["title"]=  document.getElementById('edit_title').value;
            data["author"]= document.getElementById('edit_author').value;
            data["year"]=   document.getElementById('edit_year').value;
            data["summary"]=document.getElementById('edit_summary').value;
            break;
        case "DelBook":
            data["id"]=     document.getElementById('edit_id').value;
            break;
    }
    $.ajax({
        type: "POST",
        data: data,
        url:  "templates/"+menu+"/body"+menu+".php",
        success: function (ret) {
            if(menu==="EditBook" || menu==="AddBook"){
                alert(ret);
                $("div#searchResults").html("");
            }else{
                $("div#searchResults").html(ret);
            }

        },
        error: function (ret) {
            $("div#searchResults").html("<p>Invalid menu name</p>");
        },
    });
    $("div#formEditBook").html("");

    var btn;
    switch (menu) {
        case "AddBook":
            btn=document.getElementById('menuBtnAddBook');
            setTimeout(function() {btn.click();}, 500);
            break;
        // case "GetBook":
        //
        //     break;
        case "EditBook":
            btn=document.getElementById('searchButton');
            setTimeout(function() {btn.click();}, 500);
            break;
        // case "DelBook":
        //
        //     break;
    }
    if(menu==="EditBook"){

    }
}

function ajaxLoadEditForm(bookId,trad){
    let data={};
    data["lang"]=trad;
    bookId=parseInt(bookId);
    data["id"]=     document.getElementById('book_'+bookId+'_id').innerText;
    data["title"]=  document.getElementById('book_'+bookId+'_title').innerText;
    data["author"]= document.getElementById('book_'+bookId+'_author').innerText;
    data["isbn"]=   document.getElementById('book_'+bookId+'_isbn').innerText;
    data["year"]=   document.getElementById('book_'+bookId+'_year').innerText;
    data["summary"]=document.getElementById('book_'+bookId+'_summary').innerText;

    $.ajax({
        type: "POST",
        data: data,
        url:  "templates/EditBook/formularioEditBook.php",
        success: function (ret) {
            $("div#formEditBook").html(ret);
        },
    });
}

function ajaxLoadDeleteForm(bookId,trad){
    let data={};
    data["lang"]= trad;
    data["id"]  = parseInt(bookId);
    $.ajax({
        type: "POST",
        data: data,
        url:  "templates/DelBook/bodyDelBook.php",
        success: function (ret) {
            alert(ret);
            $("div#searchResults").html("");
        },
    });
    var btn=document.getElementById('searchButton');
    setTimeout(function() {btn.click();}, 500);
}


function ajaxBookApiQuery(trad){
    let data={};
    data["lang"]=trad;
    data["isbn"]=document.getElementById('isbn_api').value;
    data["title"]=document.getElementById('title_api').value;
    data["author"]=document.getElementById('author_api').value;
    $.ajax({
        type: "POST",
        data: data,
        url:  "templates/APIGetBookInfo/bodyAPIGetBook.php",
        success: function (ret) {
            $("div#searchApiResults").html(ret);
            if(menu==="GetBook"){
                var btn=document.getElementById('searchButton');
                btn.click();
            }
        },
    });
}
