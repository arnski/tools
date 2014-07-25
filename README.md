D3GRAPHS
========

Mithilfe von ```d3graphs``` koennen die _dcContainer_-Dateien in ansprechende [D3.js](http://d3js.org/) Graphen gewandelt werden.
Als Daten Mittler dient ein leichtes _JSON_ Format, das wie folgt aufgebaut ist: 
Jedes Element besteht immer nur aus zwei Komponenten: einem ```name``` und den ```children```.

Hier ein Beispiel:
```javascript
{
    "name": "RELATEDBOX",
    "children": [{
        "name": "HEADLINE : text",
        "size": 905
    }, {
        "name": "LINKS (1:10)",
        "children": [{
            "name": "CONTENT_ID : text",
            "size": 940
        }, {
            "name": "SPITZMARKE : text",
            "size": 547
        }, {
            "name": "TEXT : text",
            "size": 799
        }]
    }]
}
```


xml2grpah.php
-------------

Mit dem Skript ```xml2graph.php``` kann man aus dem Xml ein geschachteltes Json-Objekt auslesen.
Dieses kann dann für die D3-Seite ```TreeView.html``` als Input dienen. Sie erzeugt den Graphen (im Browser).

1. Bsp für das Umformen des Xml -> Json:
```bash
php xml2graph.php navigation.cfg > json/navigation.json
```

2. Beispiel Aufruf im Borwser:
```
[file:///C:/User/CMSTR/TreeView.html?data=json/navigation]
```

-----


Die Vorlage für diese Visualisierung kommt von: [http://bl.ocks.org/robschmuecker/7880033](http://bl.ocks.org/robschmuecker/7880033)

Alternativ koennte man auch noch ein paar andere Visualiserungen in Betracht ziehen:
z.B. [http://bl.ocks.org/mbostock/4063530](http://bl.ocks.org/mbostock/4063530), [http://orgo.stolarsky.com/](http://orgo.stolarsky.com/) ... oder irgendeins von diesen [https://github.com/mbostock/d3/wiki/Gallery](https://github.com/mbostock/d3/wiki/Gallery).

.
