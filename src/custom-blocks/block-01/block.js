import icon from '../../icon.js'
const { __ } = wp.i18n
const { registerBlockType } = wp.blocks
const { RichText, InnerBlocks } = wp.blockEditor
const allowedBlocks = ['mr-blocks/block-02']


registerBlockType('mr-blocks/block-01', {
    title: __('Recipe Meta', 'my-recipes-blocks'),
    category: 'my-recipes',
    icon: icon,
    attributes: {
        hardLevel: {
            type: 'string'
		},
		persons: {
            type: 'string'
		},
		timing: {
            type: 'string'
        },
       
    },

    edit: props => {
        const {
            attributes: { hardLevel, persons, timing },
            setAttributes,
        } = props


        return (
           <div className="mr-grid">
                <RichText
                    tagName='p'
                    placeholder={__('Insert hard level', 'my-recipes')}
                    onChange={(hardLevel) => setAttributes({hardLevel})}
                    value={hardLevel}
                    keepPlaceholderOnFocus={true}
                />
				 <RichText
                    tagName='p'
                    placeholder={__('Insert timing', 'my-recipes')}
                    onChange={(timing) => setAttributes({timing})}
                    value={timing}
                    keepPlaceholderOnFocus={true}
                />
				 <RichText
                    tagName='p'
                    placeholder={__('Insert persons', 'my-recipes')}
                    onChange={(persons) => setAttributes({persons})}
                    value={persons}
                    keepPlaceholderOnFocus={true}
                />
				<InnerBlocks allowedBlocks={allowedBlocks} />
           </div>
           
            
        )
    },

    save: props => {

		const {
            attributes: { hardLevel, persons, timing }
		} = props
        return (
			<div>
					<p>
						<i class="fa fa-chart-bar"></i>
						<span>Niveli i veshtirsise {hardLevel}</span>
					</p>
					<p>
					<i class="fa fa-clock"></i>
						<span>Koha e pergatitjes {timing}</span>
					</p>
					<p>
						<i class="fa fa-chart-bar"></i>
						<span>Perbersit per  {persons} persona</span>
					</p>
					<InnerBlocks.Content />
			</div>
		)
    }
})