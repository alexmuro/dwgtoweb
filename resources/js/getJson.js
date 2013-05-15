function getJson(jURL)
{
  console.log(jURL);
  var output;
  $.ajax({
      url: jURL,
      async:false,
      dataType: "json",
      beforeSend: function(x) {
        
      },
      success: function(data){        
      output = data;
      },//success
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("XMLHttpRequest="+XMLHttpRequest.responseText+"\ntextStatus="+textStatus+"\nerrorThrown="+errorThrown);
      }
    });
    return output;
}