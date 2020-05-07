import icon from '../../icon.js'
const { __ } = wp.i18n
const { registerBlockType } = wp.blocks
const { RichText } = wp.blockEditor

registerBlockType('mr-blocks/block-03', {
    title: __('Preparation Method', 'my-recipes-blocks'),
    category: 'my-recipes',
    icon: icon,
    attributes: {
        content: {
            type: 'string'
        },
        title: {
            type: 'string'
		},
    },

    edit: props => {
        const {
            attributes: { content, title },
            setAttributes,
        } = props


        return (
				 <div>
                    <RichText
                        tagName='h3'
                        placeholder={__('Insert title', 'my-recipes')}
                        onChange={(title) => setAttributes({title})}
                        value={title}
                        keepPlaceholderOnFocus={true}
                    />  
                    <RichText
                        tagName='p'
                        placeholder={__('Insert content', 'my-recipes')}
                        onChange={(content) => setAttributes({content})}
                        value={content}
                        keepPlaceholderOnFocus={true}
                    />  
                 </div>          
        )
    },

    save: props => {

		const {
            attributes: { content, title }
		} = props
        return (
			<div>
                    <RichText.Content 
                            tagName="h3"
                            value={title}
                        />
					<RichText.Content 
                        tagName="p"
                        value={content}
                    />
					
			</div>
		)
    }
})