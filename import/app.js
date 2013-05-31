/**
 * Copyright (c) 2008-2011 The Open Source Geospatial Foundation
 * 
 * Published under the BSD license.
 * See http://svn.geoext.org/core/trunk/geoext/license.txt for the full text
 * of the license.
 */

/** api: example[tree]
 *  Tree Nodes
 *  ----------
 *  
  all kinds of tree nodes.
 */


var vector = new OpenLayers.Layer.Vector("vector");
map = new OpenLayers.Map({
            div: "map",
            allOverlays: true,
            maxExtent:[-60000, -60000, 60000, 60000],
            projection:  new OpenLayers.Projection("EPSG:4326"),
            controls: [
            new OpenLayers.Control.TouchNavigation({
                dragPanOptions: {
                    enableKinetic: true
                }
            }),
                new OpenLayers.Control.Zoom()
            ]});

map.addControl(
                new OpenLayers.Control.MousePosition({
                    prefix: '-',
                    separator: ' | ',
                    numDigits: 4
,                    emptyString: 'x|x'
                })
            );
map.fractionalZoom = true;
map.addLayers([vector])

Ext.onReady(function() {
    // create a map panel with some layers that we will show in our layer tree
    // below.

    var ctrl, toolbarItems = [], action, actions = {};



    // Navigation control and DrawFeature controls
    // in the same toggle group
    action = new GeoExt.Action({
        text: "nav",
        control: new OpenLayers.Control.Navigation(),
        map: map,
        // button options
        toggleGroup: "draw",
        allowDepress: false,
        pressed: true,
        tooltip: "navigate",
        // check item options
        group: "draw",
        checked: true
    });
    actions["nav"] = action;
    toolbarItems.push(action);

    polyOptions = {sides: 4,irregular:true};
    polygonControl = new OpenLayers.Control.DrawFeature(vector,
                                            OpenLayers.Handler.RegularPolygon,
                                            {handlerOptions: polyOptions});

    polygonControl.handler.callbacks.create  = function(data) {
        if(vector.features.length > 0)
        {
            vector.removeAllFeatures();
        }
    }

    action = new GeoExt.Action({
        text: "draw poly",
        control: polygonControl,
        map: map,
        // button options
        toggleGroup: "draw",
        allowDepress: false,
        tooltip: "draw polygon",
        // check item options
        group: "draw"
    });
    actions["draw_poly"] = action;
    toolbarItems.push(action);

    // SelectFeature control, a "toggle" control
    action = new GeoExt.Action({
        text: "select",
        control: new OpenLayers.Control.SelectFeature(vector, {
            type: OpenLayers.Control.TYPE_TOGGLE,
            hover: true
        }),
        map: map,
        // button options
        enableToggle: true,
        tooltip: "select feature"
    });
    actions["select"] = action;
    toolbarItems.push(action);
    toolbarItems.push("-");

    // Navigation history - two "button" controls
    ctrl = new OpenLayers.Control.NavigationHistory();
    map.addControl(ctrl);

    action = new GeoExt.Action({
        text: "<-",
        control: ctrl.previous,
        disabled: true,
        tooltip: "previous in history"
    });
    actions["previous"] = action;
    toolbarItems.push(action);

    action = new GeoExt.Action({
        text: "->",
        control: ctrl.next,
        disabled: true,
        tooltip: "next in history"
    });
    actions["next"] = action;
    toolbarItems.push(action);
    toolbarItems.push("->");

    // Reuse the GeoExt.Action objects created above
    // as menu items
    // toolbarItems.push({
    //     text: "menu",
    //     menu: new Ext.menu.Menu({
    //         items: [
    //             // Nav
    //             new Ext.menu.CheckItem(actions["nav"]),
    //             // Draw poly
    //             new Ext.menu.CheckItem(actions["draw_poly"]),
    //             // Navigation history control
    //             actions["previous"],
    //             actions["next"]
    //         ]
    //     })
    // });


    mapPanel = new GeoExt.MapPanel({
        border: true,
        region: "center",
        map: map,
        tbar: toolbarItems
  
    });

    // create our own layer node UI class, using the TreeNodeUIEventMixin
    var LayerNodeUI = Ext.extend(GeoExt.tree.LayerNodeUI, new GeoExt.tree.TreeNodeUIEventMixin());
        
    // using OpenLayers.Format.JSON to create a nice formatted string of the
    // configuration for editing it in the UI
    var treeConfig = [{
        nodeType: "gx_baselayercontainer"
    }, {
        nodeType: "gx_overlaylayercontainer",
        expanded: true,        // render the nodes inside this container with a radio button,
        // and assign them the group "foo".
        loader: {
            baseAttrs: {
                radioGroup: "foo",
                uiProvider: "layernodeui"
            }
        }
    }
    ];
    // The line below is only needed for this example, because we want to allow
    // interactive modifications of the tree configuration using the
    // "Show/Edit Tree Config" button. Don't use this line in your code.
    treeConfig = new OpenLayers.Format.JSON().write(treeConfig, true);

    // create the tree with the configuration from above
    tree = new Ext.tree.TreePanel({
        border: true,
        region: "west",
        title: "Layers",
        width: 200,
        split: true,
        collapsible: true,
        collapseMode: "mini",
        collapsed:true,
        autoScroll: true,
        plugins: [
            new GeoExt.plugins.TreeNodeRadioButton({
                listeners: {
                    "radiochange": function(node) {
                        alert(node.text + " is now the active layer.");
                    }
                }
            })
        ],
        loader: new Ext.tree.TreeLoader({
            // applyLoader has to be set to false to not interfer with loaders
            // of nodes further down the tree hierarchy
            applyLoader: false,
            uiProviders: {
                "layernodeui": LayerNodeUI
            }
        }),
        root: {
            nodeType: "async",
            // the children property of an Ext.tree.AsyncTreeNode is used to
            // provide an initial set of layer nodes. We use the treeConfig
            // from above, that we created with OpenLayers.Format.JSON.write.
            children: Ext.decode(treeConfig)
            // Don't use the line above in your application. Instead, use
            //children: treeConfig
            
        },
        listeners: {
            "radiochange": function(node){
                alert(node.layer.name + " is now the the active layer.");
            }
        },
        rootVisible: false,
        lines: false,
        bbar: [{
            text: "Show/Edit Tree Config",
            handler: function() {
                treeConfigWin.show();
                Ext.getCmp("treeconfig").setValue(treeConfig);
            }
        }]
    });

    // dialog for editing the tree configuration
    var treeConfigWin = new Ext.Window({
        layout: "fit",
        hideBorders: true,
        closeAction: "hide",
        width: 300,
        height: 400,
        title: "Tree Configuration",
        items: [{
            xtype: "form",
            layout: "fit",
            items: [{
                id: "treeconfig",
                xtype: "textarea"
            }],
            buttons: [{
                text: "Save",
                handler: function() {
                    var value = Ext.getCmp("treeconfig").getValue()
                    try {
                        var root = tree.getRootNode();
                        root.attributes.children = Ext.decode(value);
                        tree.getLoader().load(root);
                    } catch(e) {
                        alert("Invalid JSON");
                        return;
                    }
                    treeConfig = value;
                    treeConfigWin.hide();
                }
            }, {
                text: "Cancel",
                handler: function() {
                    treeConfigWin.hide();
                }
            }]
        }]
    });

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
    
    new Ext.Viewport({
        layout: "fit",
        hideBorders: true,
        items: {
            layout: "border",
            deferredRender: false,
            items: [mapPanel, tree, {
                contentEl: "desc",
                region: "east",
                bodyStyle: {"padding": "5px"},
                collapsible: true,
                collapseMode: "mini",
                split: true,
                width: 400
            }]
        }
    });
});
