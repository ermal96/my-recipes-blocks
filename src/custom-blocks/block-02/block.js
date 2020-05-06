import icon from '../../icon.js'
const { __ } = wp.i18n
const { registerBlockType } = wp.blocks
const { RichText } = wp.blockEditor

registerBlockType('mr-blocks/block-02', {
    title: __('Ingredients', 'my-recipes-blocks'),
    parent: ['mr-blocks/block-01'],
    category: 'my-recipes',
    icon: icon,
    attributes: {
        ingredients: {
            type: 'string'
        }

    },
    edit: props => {
        const {
            attributes: { ingredients},
            setAttributes
        } = props

        return (
            <React.Fragment>
                 <RichText
                    tagName='p'
                    placeholder={__('Insert ingredients', 'my-recipes')}
                    onChange={(ingredients) => setAttributes({ingredients})}
                    value={ingredients}
                    keepPlaceholderOnFocus={true}
        />
            </React.Fragment>
           
       
        )
    },
    save: props => {
        const {
            attributes: { ingredients},
        } = props
        return (
            <RichText.Content
                    tagName='p'
                    value={ingredients}
        />
        )
    }
})