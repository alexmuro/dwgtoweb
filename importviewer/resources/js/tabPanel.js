    var tabs = new Ext.TabPanel({
        width:199,
        region: "west",
        split: true,
        activeTab: 0,
        frame:true,
        autoScroll: true,
        items:[
            {
                autoLoad: 
                    {
                        url: 'LayerListing.php',
                        callback: function() {
                            llpageload();
                         }
                    },
                    title:'View'
            },
            {
                autoLoad: 
                    {
                        url: '../upload/uploader.html',
                        callback: function() {
                            console.log('upload page load');
                            //uploader('drop', 'status', '/dwgtoweb/uploader.php', 'list');
                            startUploader();
                         }
                    },
                    title:'Upload'
            }
        ]
        
    });