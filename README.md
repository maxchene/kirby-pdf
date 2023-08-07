# Kirby PDF

This plugin allow you to choose a PDF engine to render HTML as PDF.

## Rendering

### Rendering with current template

This is the simplest render. PDF engine will parse the current page with her current template and css to render the PDF
file.

For this rendering, you might want to use [print media queries](https://developer.mozilla.org/en-US/docs/Web/CSS/@media)
 and [page media query](https://developer.mozilla.org/fr/docs/Web/CSS/@page) to fit your needs.

### Dedicated pdf template

Sometimes rendering a pdf with media queries can be tricky, and sometimes you'll want a different rendering between HTML
page and PDF file for the same data.

In this case, you can configure a dedicated template for the PDF rendering using a custom css file and custom HTML tree.

To activate dedicated template, you have to create a new template with the same name as your page template in
the ``pdf``
subdirectory of your ``templates`` folder.

This is an example with a ``recipe`` page template :

```
├── snippets
├── templates
│   ├── pdf
│   │   ├── recipe.php   <- used to render the PDF file
│   ├── recipe.php       <- used to render the HTML page
```

So, in the ``templates/pdf/recipe.php``, you can use a totally different layout and css to render the PDF file.

