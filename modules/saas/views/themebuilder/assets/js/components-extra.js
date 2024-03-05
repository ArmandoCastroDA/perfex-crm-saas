/*
 * Custom elements
 */
Vvveb.ComponentsGroup["Elements"].push("html/iframe");

Vvveb.Components.extend("_base", "html/iframe", {
	nodes: ["iframe"],
	name: "Iframe",
	html: '<iframe class="raw-html-code w-full h-full iframe py-4" src="https://docs.perfextosaas.com" height="100%" width="100%" sandbox="allow-top-navigation allow-forms allow-same-origin allow-popups allow-scripts" frameborder="0" allowfullscreen="allowfullscreen"></iframe>',
	image: "icons/stream-solid.svg",
	properties: [
		{
			name: "Url",
			key: "src",
			htmlAttr: "src",
			inputtype: LinkInput,
		},
		{
			name: "Width",
			key: "width",
			htmlAttr: "width",
			inputtype: TextInput,
		},
		{
			name: "Height",
			key: "height",
			htmlAttr: "height",
			inputtype: TextInput,
		},
		{
			name: "Sandbox property",
			key: "sandbox",
			htmlAttr: "sandbox",
			inputtype: TextInput,
		},
		{
			name: "Frame border",
			key: "frameborder",
			htmlAttr: "frameborder",
			inputtype: NumberInput,
		},
	],
});
