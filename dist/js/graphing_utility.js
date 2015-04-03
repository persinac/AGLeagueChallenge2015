/**
 * Created by APersinger on 12/30/14.
 */

function graphTeamTotalKills(data2) {
    //console.log(data2);
    var data = new Array();

    //console.log(data.length);

    for (var key in data2) {
        if (data2.hasOwnProperty(key)) {
            var result = {};
            //console.log(data2[key]['name'] + ", " +data2[key]['value'] + ", "+data2[key]['color'] );
            var color = "#428bda"; // defaults to blue team color
            if(data2[key]['name'] == "team2") {
                color = "#8249ce";
            }

            result.name = data2[key]['name'];
            result.value = data2[key]['value'];
            result.color = color;
            data.push( result );

        }
    }


    var width = 150;
    var height = 200;
    var barPadding = 1;

    var margin = {
        top: 0,
        right: 0,
        bottom: 0,
        left: 0
    };
    //console.log(data);

    var xScale = d3.scale.linear()
        .domain([0, d3.max(data, function(d){ return d.value; })])
        .rangeRound([0, width], 0.05);

    var yScale = d3.scale.linear()
        .domain([0, d3.max(data, function(d) { return d.value; })])
        .range([0, height]);

    var svg = d3.select("#total_team_kills")
        .append("svg")
        .attr("width", width)
        .attr("height", height);

    svg.selectAll("rect")
        .data(data,function(d){return d.value;})
        .enter()
        .append("rect")
        .attr("x", function(d, i) {
            console.log("i: " + i + " width: " + width + " data.length: " +data.length + "barpadding: " +barPadding );
            console.log("i * (width / data.length - barPadding): " +(i * (width / data.length - barPadding)));
            return i * (width / data.length);
        })
        .attr("y", function(d, i) {
            console.log("height: " + height + " d.value: " + d.value);
            return height - yScale(d.value);  //Height minus data value
            //return yScale(d.value);
        })
        .attr("width", width / data.length - barPadding)
        .attr("height", function(d){
            console.log("RECT yScale("+ d.value+"): " + yScale(d.value));
            return yScale(d.value); })
        .attr("fill", function(datum) { return datum.color; });

    svg.selectAll("text")
        .data(data,function(d){return d.value;})
        .enter()
        .append("svg:text")
        .attr("x", function(d, i) {
            /*console.log("X, width: " + width + ", i: " + i);
            console.log("X, xScale("+ d.value+"): " + xScale(d.value));
            console.log("X, xScale("+ d.value+") ("+xScale(d.value)+") + "+width+": " + (xScale(d.value) + width));*/
            /**
             * bad way to do things...but since I know where the bars are, I hardcode the x locations...
             */
            var new_x = 0;

            if(i == 0) {
                new_x = 40;
            } else {
                new_x = 120;
            }

            //return xScale(d.value) + width;
            return new_x;})
        .attr("y", function(d) {
            /*console.log("Y, height: " + height);
            console.log("Y, yScale("+ d.value+"): " + yScale(d.value));
            console.log("Y, height - yScale("+ d.value+"): "+height+" - ("+yScale(d.value)+"): " + (height - yScale(d.value)));
*/
            var new_y = yScale(d.value);
            if(new_y == height) {
                new_y = 20;
            } else {
                new_y = (height - new_y) + 20;
            }

            return new_y; })
        .text(function(d) {
            console.log("TEXT... d.value: " + d.value);
            return d.value;
        });

    /*


     var barWidth = 40, barHeight = 20;


    // add the canvas to the DOM
    var chart = d3.select("#total_team_kills").
        append("svg").
        attr("width", width).
        attr("height", height);

    var y = d3.scale.linear().range([height, 0])
        .domain([0, d3.max(data, function(d) { return d.value; })]);

    var x = d3.scale.linear()
        .domain([0, d3.max(data,function(d){console.log(d.value);return d.value})])
        .range([0, width]);

    var bar = chart.selectAll("g")
        .data(data, function(d) { return d.value; })
        .enter().append("g")
        .attr("transform", function(d, i) { return "translate(" + i * barWidth + ",0)"; });

    var barWidth = width / data.length;

    var bar = chart.selectAll("g")
        .data(data, function(d){ return d.value;})
        .enter()
        .append("g")
        .attr("transform", function(d, i) { return "translate(" + i * barWidth + ",0)"; });

    bar.append("rect")
        .attr("y", function(datum) { return buildYforRectAppend(datum); })
        .attr("height", function(datum) { return height - y(datum.value); })
        .attr("width", barWidth - 1)
        .attr("fill", function(datum) { return datum.color; });;

    bar.append("text")
        .attr("x", barWidth / 2)
        .attr("y", function(d) { return y(d.value) + 3; })
        .attr("dy", ".75em")
        .text(function(d) { return d.value; });
     */

    function buildYforRectAppend(datum) {
        console.log("append rect 'y': " + y(datum.value));
        return y(datum.value);
    }
}

/**
 *
 * @param data - json object with summonerIds and summonerNames
 */
function graph_NetworkOfSummoners(data) {
    var treeData = [
        {
            "name": "Top Level",
            "parent": "null",
            "children": [
                {
                    "name": "Level 2: A",
                    "parent": "Top Level",
                    "children": [
                        {
                            "name": "Son of A",
                            "parent": "Level 2: A"
                        },
                        {
                            "name": "Daughter of A",
                            "parent": "Level 2: A"
                        }
                    ]
                },
                {
                    "name": "Level 2: B",
                    "parent": "Top Level"
                }
            ]
        }
    ];

    console.log(treeData);
    console.log(data);
// ************** Generate the tree diagram	 *****************
    var margin = {top: 20, right: 120, bottom: 20, left: 120},
        width = 960 - margin.right - margin.left,
        height = 500 - margin.top - margin.bottom;

    var i = 0,
        duration = 750,
        root;

    var tree = d3.layout.tree()
        .size([height, width]);

    var diagonal = d3.svg.diagonal()
        .projection(function(d) { return [d.y, d.x]; });

    var svg = d3.select("#test_graph_stuff").append("svg")
        .attr("width", width + margin.right + margin.left)
        .attr("height", height + margin.top + margin.bottom)
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    root = data[0];
    //root = treeData[0];
    root.x0 = height / 2;
    root.y0 = 0;

    update(root);

    d3.select(self.frameElement).style("height", "500px");

    function update(source) {

        // Compute the new tree layout.
        var nodes = tree.nodes(root).reverse(),
            links = tree.links(nodes);

        // Normalize for fixed-depth.
        nodes.forEach(
            function(d) {
                //d.y = d.depth * 180; };
                d.y = d.depth * 180;
                /*if (d.parent != null) {
                    d.x = d.parent.x - (d.parent.children.length - 1) * 30 / 2
                    + (d.parent.children.indexOf(d)) * 30;
                }
                // if the node has too many children, go in and fix their positions to two columns.
                if (d.children != null && d.children.length > 4) {
                    d.children.forEach(function (d, i) {
                        d.y = (d.depth * 180 + i % 10 * 100);
                        d.x = d.parent.x - (d.parent.children.length - 1) * 30 / 4
                        + (d.parent.children.indexOf(d)) * 30 / 2 - i % 10 * 15;
                    });
                }*/
            }
        );

        // Update the nodes…
        var node = svg.selectAll("g.node")
            .data(nodes, function(d) { return d.id || (d.id = ++i); });

        // Enter any new nodes at the parent's previous position.
        var nodeEnter = node.enter().append("g")
            .attr("class", "node")
            .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
            .on("click", click);

        nodeEnter.append("circle")
            .attr("r", 1e-6)
            .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

        nodeEnter.append("text")
            .attr("x", function(d) { return d.children || d._children ? -13 : 13; })
            .attr("dy", ".35em")
            .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
            .text(function(d) { return d.name; })
            .style("fill-opacity", 1e-6);

        // Transition nodes to their new position.
        var nodeUpdate = node.transition()
            .duration(duration)
            .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

        nodeUpdate.select("circle")
            .attr("r", 10)
            .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

        nodeUpdate.select("text")
            .style("fill-opacity", 1);

        // Transition exiting nodes to the parent's new position.
        var nodeExit = node.exit().transition()
            .duration(duration)
            .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
            .remove();

        nodeExit.select("circle")
            .attr("r", 1e-6);

        nodeExit.select("text")
            .style("fill-opacity", 1e-6);

        // Update the links…
        var link = svg.selectAll("path.link")
            .data(links, function(d) { return d.target.id; });

        // Enter any new links at the parent's previous position.
        link.enter().insert("path", "g")
            .attr("class", "link")
            .attr("d", function(d) {
                var o = {x: source.x0, y: source.y0};
                return diagonal({source: o, target: o});
            });

        // Transition links to their new position.
        link.transition()
            .duration(duration)
            .attr("d", diagonal);

        // Transition exiting nodes to the parent's new position.
        link.exit().transition()
            .duration(duration)
            .attr("d", function(d) {
                var o = {x: source.x, y: source.y};
                return diagonal({source: o, target: o});
            })
            .remove();

        // Stash the old positions for transition.
        nodes.forEach(function(d) {
            d.x0 = d.x;
            d.y0 = d.y;
        });
    }

// Toggle children on click.
    function click(d) {
        if (d.children) {
            d._children = d.children;
            d.children = null;
        } else {
            d.children = d._children;
            d._children = null;
        }
        update(d);
    }

}

function graph_FD_SummonerNetwork(mydata) {
    var data = {"nodes":[
        {"name":"YHO", "full_name":"Yahoo", "type":1, "slug": "www.yahoo.com", "entity":"company", "img_hrefD":"", "img_hrefL":""},
        {"name":"GGL", "full_name":"Google", "type":2, "slug": "www.google.com", "entity":"company", "img_hrefD":"", "img_hrefL":""},
        {"name":"BNG", "full_name":"Bing", "type":2, "slug": "www.bing.com", "entity":"company", "img_hrefD":"", "img_hrefL":""},
        {"name":"ALE", "full_name":"Yandex", "type":2, "slug": "www.yandex.com", "entity":"company", "img_hrefD":"", "img_hrefL":""},

        {"name":"Desc1", "type":4, "slug": "", "entity":"description"},
        {"name":"Desc2", "type":4, "slug": "", "entity":"description"},
        {"name":"Desc4", "type":4, "slug": "", "entity":"description"},

        {"name":"CEO", "prefix":"Mr.", "fst_name":"Jim", "snd_name":"Bean", "type":3, "slug": "", "entity":"employee"},
        {"name":"ATT", "prefix":"Ms.", "fst_name":"Jenna", "snd_name":"Jameson", "type":3, "slug": "", "entity":"employee"},
        {"name":"CTO", "prefix":"Mr.", "fst_name":"Lucky", "snd_name":"Luke", "type":3, "slug": "", "entity":"employee"},
        {"name":"CDO", "prefix":"Ms.", "fst_name":"Pamela", "snd_name":"Anderson", "type":3, "slug": "", "entity":"employee"},
        {"name":"CEO", "prefix":"Mr.", "fst_name":"Nacho", "snd_name":"Vidal", "type":3, "slug": "", "entity":"employee"},

        {"name":"Desc5", "type":4, "slug": "", "entity":"description"},
    ],
        "links":[
            {"source":0,"target":4,"value":1,"distance":5},
            {"source":0,"target":5,"value":1,"distance":5},
            {"source":0,"target":6,"value":1,"distance":5},

            {"source":1,"target":4,"value":1,"distance":5},
            {"source":2,"target":5,"value":1,"distance":5},
            {"source":3,"target":6,"value":1,"distance":5},

            {"source":7,"target":3,"value":10,"distance":6},
            {"source":8,"target":3,"value":10,"distance":6},
            {"source":9,"target":1,"value":10,"distance":6},
            {"source":10,"target":1,"value":10,"distance":6},

            {"source":11,"target":12,"value":10,"distance":6},
            {"source":12,"target":2,"value":10,"distance":6},
        ]
    };
	mydata = JSON.parse(mydata);
    //console.log(mydata);
    var t_width = $("#test_graph_stuff").width();
    var t_height = $(document).height();
    console.log("WIDTH: " + t_width + " HEIGHT: " + t_height);
    //$("#mydata1").html("<h3>MYDATA</h3>"+JSON.stringify(mydata)+"<h3>ORIGDATA</h3>"+JSON.stringify(data));
	data = mydata;
    var w = t_width,
        h = t_height,
        radius = d3.scale.log().domain([0, 150000]).range(["10", "50"]);

    var vis = d3.select("#test_graph_stuff").append("svg:svg")
        .attr("id", "mysvgele")
        .attr("width", w)
        .attr("height", h);

    //vis.append("defs").append("marker")
    //.attr("id", "arrowhead")
    //.attr("refX", 22 + 3) /*must be smarter way to calculate shift*/
    //.attr("refY", 2)
    //.attr("markerWidth", 6)
    //.attr("markerHeight", 4)
    //.attr("orient", "auto")
    //.append("path")
    //.attr("d", "M 0,0 V 4 L6,2 Z"); //this is actual shape for arrowhead

    var force = self.force = d3.layout.force()
        .nodes(data.nodes)
        .links(data.links)
        .linkDistance(function(d) { return (d.distance*10); })
        .charge(-500)
        .size([w, h])
        .start();

    var link = vis.selectAll("line.link")
        .data(data.links)
        .enter().append("svg:line")
        .attr("class", function (d) { return "link" + d.value +""; })
        .attr("x1", function(d) {
            //console.log("x1 d.source: " + d.source);
            //console.log("x1 d.source.x: " + d.source.x);
            return d.source.x;
        })
        .attr("y1", function(d) {
            //console.log("y1 d.source: " + d.source);
            //console.log("y1 d.source.y: " + d.source.y);
            return d.source.y; })
        .attr("x2", function(d) {
            //console.log("x2 d.target: " + d.target);
            //console.log("x2 d.target.x: " + d.target.x);
            return d.target.x; })
        .attr("y2", function(d) {
            //console.log("y2 d.target: " + d.target);
            //console.log("y2 d.target.y: " + d.target.y);
            return d.target.y; })
        .attr("marker-end", function(d) {
            if (d.value == 1) {return "url(#arrowhead)"}
            else    { return " " }
            ;});




    function openLink() {
        return function(d) {
            var url = "";
            if(d.slug != "") {
                url = d.slug
            } //else if(d.type == 2) {
            //url = "clients/" + d.slug
            //} else if(d.type == 3) {
            //url = "agencies/" + d.slug
            //}
            window.open("//"+url)
        }
    }




    var node = vis.selectAll("g.node")
        .data(data.nodes)
        .enter().append("svg:g")
        .attr("class", "node")
        .call(force.drag);


    node.append("circle")
        .attr("class", function(d){ return "node type"+d.type})
        .attr("r",function(d){
            var size = 10;
            if(d.name.length <= 7) {
                size = 20;
            } else if(d.name.length > 7 && d.name.length < 25) {
                size = 45;
            } else {
                size = 60;
            }
            return size;
        });
        /*.attr('cx', function(d) {
            console.log(d.x);
        })
        .attr('cy', function(d) {
            console.log(d.y);
            var new_y = 0;
            if(d.y > 500) {
                new_y = d.y - 500;
            } else {
                new_y = d.y + 250;
            }
            return new_y;
        });*/


    node.append("text")
        .attr("class", function(d){ return "nodetext title_"+d.name })
        .attr("dx", 0)
        .attr("dy", ".35em")
        .style("font-size","10px")
        .attr("text-anchor", "middle")
        .style("fill", "white")
        .text(function(d) { if (d.entity != "description"){return d.name} });



    node.on("mouseover", function (d) {
        if (d.entity == "company"){
            d3.select(this).select('text')
                .transition()
                .duration(300)
                .text(function(d){
                    return d.full_name;
                })
                .style("font-size","15px")

        }
        else if(d.entity == "employee"){
            var asdf = d3.select(this);
            asdf.select('text').remove();

            asdf.append("text")
                .text(function(d){return d.prefix + ' ' + d.fst_name })
                .attr("class","nodetext")
                .attr("dx", 0)
                .attr("dy", ".35em")
                .style("font-size","5px")
                .attr("text-anchor", "middle")
                .style("fill", "white")
                .transition()
                .duration(300)
                .style("font-size","12px");

            asdf.append("text").text(function(d){return d.snd_name })
                .attr("class","nodetext")
                .attr("transform","translate(0, 12)")
                .attr("dx", 0)
                .attr("dy", ".35em")
                .style("font-size","5px")
                .attr("text-anchor", "middle")
                .style("fill", "white")
                .transition()
                .duration(300)
                .style("font-size","12px");
        }
        else {
            d3.select(this).select('text')
                .transition()
                .duration(300)
                .style("font-size","15px")
        }

        if (d.entity == "company") {
            d3.select(this).select('image')
                .attr("width", "90px")
                .attr("x", "-46px")
                .attr("y", "-36.5px")
                .attr("xlink:href", function (d) {
                    return d.img_hrefL
                });
        }

        if (d.entity == "company") {
            d3.select(this).select('circle')
                .transition()
                .duration(300)
                .attr("r",28)

        }
        else if (d.entity == "employee"){
            d3.select(this).select('circle')
                .transition()
                .duration(300)
                .attr("r",32)
        }
    })


    node.on("mouseout", function (d) {
        if (d.entity == "company") {
            d3.select(this).select('text')
                .transition()
                .duration(300)
                .text(function(d){return d.name;})
                .style("font-size","10px")
        }
        else if(d.entity == "employee"){
            ///////////////////////////
            // CHANGE
            ///////////////////////////

            d3.select(this).selectAll('text').remove();

            //d3.select(this).select('text')
            d3.select(this).append('text')
                .text(function(d){return d.name;})
                .style("font-size","14px")
                .attr("dx", 0)
                .attr("dy", ".35em")
                .attr("text-anchor", "middle")
                .style("fill", "white")
                .attr("class","nodetext")
                .transition()
                .duration(300)
                .style("font-size","10px")

        }
        else {
            d3.select(this).select('text')
                .transition()
                .duration(300)
                .style("font-size","10px")
        }


        if (d.entity == "company") {
            d3.select(this).select('image')
                .attr("width", "70px")
                .attr("x", "-36px")
                .attr("y", "-36px")
                .attr("xlink:href", function (d) {
                    return d.img_hrefD
                });
        }

        if (d.entity == "company" || d.entity == "employee") {

            d3.select(this).select('circle')
                .transition()
                .duration(300)
                .attr("r",18)
        }

    });

    node.on("mouseover", fade(.4,"red"))
        .on("mouseout", fade(1));

    var linkedByIndex = {};
    data.links.forEach(function(d) {
        linkedByIndex[d.source.index + "," + d.target.index] = 1;
    });

    function isConnected(a, b) {
        return linkedByIndex[a.index + "," + b.index] || linkedByIndex[b.index + "," + a.index] || a.index == b.index;
    }

    force.on("tick", function() {
        link.attr("x1", function(d) { return d.source.x; })
            .attr("y1", function(d) { return d.source.y; })
            .attr("x2", function(d) { return d.target.x; })
            .attr("y2", function(d) { return d.target.y; });
        node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
    });

    function fade(opacity,color) {
        return function(d) {

            node.style("stroke-opacity", function(o) {
                thisOpacity = isConnected(d, o) ? 1 : opacity;
                this.setAttribute('fill-opacity', thisOpacity);
                return thisOpacity;
            });

            link.style("stroke-opacity", function(o) {
                return o.source === d || o.target === d ? 1 : opacity;
            })

                .style("stroke", function(o) {
                    return o.source === d || o.target === d ? color : "#000" ;
                });
        };

    }
}
