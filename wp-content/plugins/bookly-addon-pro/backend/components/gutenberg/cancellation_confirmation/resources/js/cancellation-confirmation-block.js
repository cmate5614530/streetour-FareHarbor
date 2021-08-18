(function (wp) {
    var el = wp.element.createElement;

    wp.blocks.registerBlockType('bookly/cancellation-confirmation', {
        title: BooklyCancellationConfirmationL10n.block.title,
        description: BooklyCancellationConfirmationL10n.block.description,
        icon: el('svg', { width: '20', height: '20', viewBox: "0 0 64 64" },
            el('path', {style: {fill: "rgb(0, 0, 0)"}, d: "M 8 0 H 56 A 8 8 0 0 1 64 8 V 22 H 0 V 8 A 8 8 0 0 1 8 0 Z"}),
            el('path', {style: {fill: "rgb(244, 102, 47)"}, d: "M 0 22 H 64 V 56 A 8 8 0 0 1 56 64 H 8 A 8 8 0 0 1 0 56 V 22 Z"}),
            el('rect', {style: {fill: "rgb(98, 86, 86)"}, x: 6, y: 6, width: 52, height: 10}),
            el('rect', {style: {fill: "rgb(242, 227, 227)"}, x: 12, y: 30, width: 40, height: 24}),
            el('path', {style: {fill: "rgb(255, 255, 0)", stroke: 'rgb(0, 0, 0)'}, d: "M 43.382 32.902 L 59.108 60.14 L 27.656 60.14 L 43.382 32.902 Z"}),
        ),
        category: 'bookly-blocks',
        keywords: [
            'bookly',
            'cancellation',
        ],
        supports: {
            customClassName: false,
            html: false
        },
        attributes: {},
        edit: function (props) {
            return [
                el('div', {},
                    '[bookly-cancellation-confirmation]'
                )
            ]
        },

        save: function (props) {
            return (
                el('div', {},
                    '[bookly-cancellation-confirmation]'
                )
            )
        }
    })
})(
  window.wp
);