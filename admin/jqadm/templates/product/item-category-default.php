<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2016
 */

$enc = $this->encoder();

?>
<div id="category" class="row item-category tab-pane fade" role="tabpanel" aria-labelledby="category">
	<div class="col-xl-6 content-block">
		<table class="category-list table table-default">
			<thead>
				<tr>
					<th><?= $enc->html( $this->translate( 'admin', 'Default' ) ); ?></th>
					<th class="actions">
						<div class="btn act-add fa" tabindex="<?= $this->get( "tabindex" ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>">
						</div>
					</th>
				</tr>
			</thead>
			<tbody>

				<?php $listTypeId = $this->get( 'categoryListTypes/default' ); ?>
				<?php foreach( $this->get( 'categoryData/catalog.lists.id', [] ) as $idx => $id ) : ?>
					<?php if( $this->get( 'categoryData/catalog.lists.typeid/' . $idx ) == $listTypeId ) : ?>
						<tr>
							<td>
								<input class="item-listtypeid" type="hidden"
									name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.typeid', '' ) ) ); ?>"
									value="<?= $enc->attr( $listTypeId ); ?>" />
								<input class="item-listid" type="hidden"
									name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.id', '' ) ) ); ?>"
									value="<?= $enc->attr( $id ); ?>" />
								<input class="item-label" type="hidden"
									name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.label', '' ) ) ); ?>"
									value="<?= $enc->attr( $this->get( 'categoryData/catalog.label/' . $idx ) ); ?>" />
								<select class="combobox item-id" tabindex="<?= $this->get( "tabindex" ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.id', '' ) ) ); ?>">
									<option value="<?= $enc->attr( $this->get( 'categoryData/catalog.id/' . $idx ) ); ?>" >
										<?= $enc->html( $this->get( 'categoryData/catalog.label/' . $idx ) ); ?>
									</option>
								</select>
							</td>
							<td class="actions">
								<div class="btn act-delete fa" tabindex="<?= $this->get( "tabindex" ); ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
								</div>
							</td>
						</tr>
					<?php endif; ?>
				<?php endforeach; ?>

				<tr class="prototype">
					<td>
						<input class="item-listtypeid" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.typeid', '' ) ) ); ?>"
							value="<?= $enc->attr( $listTypeId ); ?>" />
						<input class="item-listid" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.id', '' ) ) ); ?>" />
						<input class="item-label" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.label', '' ) ) ); ?>" />
						<select class="combobox-prototype item-id" tabindex="<?= $this->get( "tabindex" ); ?>" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.id', '' ) ) ); ?>">
						</select>
					</td>
					<td class="actions">
						<div class="btn act-delete fa" tabindex="<?= $this->get( "tabindex" ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="col-xl-6 content-block">
		<table class="category-list table table-default">
			<thead>
				<tr>
					<th><?= $enc->html( $this->translate( 'admin', 'Promotion' ) ); ?></th>
					<th class="actions">
						<div class="btn act-add fa" tabindex="<?= $this->get( "tabindex" ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Add new entry (Ctrl+A)') ); ?>">
						</div>
					</th>
				</tr>
			</thead>
			<tbody>

				<?php $listTypeId = $this->get( 'categoryListTypes/promotion' ); ?>
				<?php foreach( $this->get( 'categoryData/catalog.lists.id', [] ) as $idx => $id ) : ?>
					<?php if( $this->get( 'categoryData/catalog.lists.typeid/' . $idx ) == $listTypeId ) : ?>
						<tr>
							<td>
								<input class="item-listtypeid" type="hidden"
									name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.typeid', '' ) ) ); ?>"
									value="<?= $enc->attr( $listTypeId ); ?>" />
								<input class="item-listid" type="hidden"
									name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.id', '' ) ) ); ?>"
									value="<?= $enc->attr( $id ); ?>" />
								<input class="item-label" type="hidden"
									name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.label', '' ) ) ); ?>"
									value="<?= $enc->attr( $this->get( 'categoryData/catalog.label/' . $idx ) ); ?>" />
								<select class="combobox item-id" tabindex="<?= $this->get( "tabindex" ); ?>"
									name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.id', '' ) ) ); ?>">
									<option value="<?= $enc->attr( $this->get( 'categoryData/catalog.id/' . $idx ) ); ?>" >
										<?= $enc->html( $this->get( 'categoryData/catalog.label/' . $idx ) ); ?>
									</option>
								</select>
							</td>
							<td class="actions">
								<div class="btn act-delete fa" tabindex="<?= $this->get( "tabindex" ); ?>"
									title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
								</div>
							</td>
						</tr>
					<?php	endif; ?>
				<?php endforeach; ?>

				<tr class="prototype">
					<td>
						<input class="item-listtypeid" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.typeid', '' ) ) ); ?>"
							value="<?= $enc->attr( $listTypeId ); ?>" />
						<input class="item-listid" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.lists.id', '' ) ) ); ?>" />
						<input class="item-label" type="hidden" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.label', '' ) ) ); ?>" />
						<select class="combobox-prototype item-id" tabindex="<?= $this->get( "tabindex" ); ?>" disabled="disabled"
							name="<?= $enc->attr( $this->formparam( array( 'category', 'catalog.id', '' ) ) ); ?>">
						</select>
					</td>
					<td class="actions">
						<div class="btn act-delete fa" tabindex="<?= $this->get( "tabindex" ); ?>"
							title="<?= $enc->attr( $this->translate( 'admin', 'Delete this entry') ); ?>">
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

	<?= $this->get( 'categoryBody' ); ?>
</div>
