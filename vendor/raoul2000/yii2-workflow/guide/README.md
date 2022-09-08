# User Guide

## Prerequisite

The User Guide is designed to be built by **[mkDocs](http://www.mkdocs.org/)** based on a [Bootswatch theme](https://github.com/mkdocs/mkdocs-bootswatch)

```
pip install mkdocs
pip install mkdocs-bootswatch
```

More info about **mkDocs** installation [here](http://www.mkdocs.org/#installation)

## Building the Guide

During dev, the guide can be served from a local server in charge of refreshing the page on each
change. To start the local server, enter :

```
cd guide
mkdocs serve
```

When the guide is ready to be published, build it into the folder `guide/site` with :

```
mkdocs build --clean
```

# Class Reference

## Prerequisite

The class reference documentation is built using [apiGen](http://www.apigen.org/) using the *bootstrap* built-in theme.

To install *apiGen*, [download the apigen.phar](http://apigen.org/apigen.phar) file into the `guide` folder.

## Building The Class Reference Doc

From the `guide` folder :

```
php apigen.phar generate -s ..\src -d site\class-ref\api --template-theme bootstrap --no-source-code --title "yii2-workflow Class Reference"
```

The documentation is built into the folder `guide/site/class-ref/api`.


# Github Pages

To push it to the **gh-pages** branch :

```
cd guide
mkdocs gh-deploy
```

[read more](http://www.mkdocs.org/user-guide/deploying-your-docs/)
