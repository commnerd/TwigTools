# TwigTools

## A toolchain for more easily managing Twig templates

### FormBuilder

#### Example Use In Template:
```javascript
{{
    formBuilder.textarea({
      "": "var"
    })
}}

```

#### Extra Instructions:
1. To initialize, simply call 
1. All templates must reside in a subfolder <Twig Template Folder>/TwigTools/FormBuilder/<method_name>
1. <method_name>, above, pertains to the method being called; in our example above, "textarea" is the method name
