<?php
namespace ArminVieweg\Dce\ViewHelpers;

/*  | This extension is part of the TYPO3 project. The TYPO3 project is
 *  | free software and is licensed under GNU General Public License.
 *  |
 *  | (c) 2012-2015 Armin Ruediger Vieweg <armin@v.ieweg.de>
 */

/**
 * Image viewhelper which works for preview texts as well
 *
 * @package ArminVieweg\Dce
 */
class ImageViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\ImageViewHelper {

	/**
	 * Resizes a given image (if required) and renders the respective img tag
	 *
	 * @param string $src
	 * @param string $width width of the image. This can be a numeric value representing the fixed width of
	 * 			the image in pixels. But you can also perform simple calculations by adding "m" or "c" to
	 * 			the value. See imgResource.width for possible options.
	 * @param string $height height of the image. This can be a numeric value representing the fixed height of
	 * 			the image in pixels. But you can also perform simple calculations by adding "m" or "c" to
	 * 			the value. See imgResource.width for possible options.
	 * @param int $minWidth minimum width of the image
	 * @param int $minHeight minimum height of the image
	 * @param int $maxWidth maximum width of the image
	 * @param int $maxHeight maximum height of the image
	 * @param bool $treatIdAsReference given src argument is a sys_file_reference record
	 *
	 * @return string rendered tag.
	 * @deprecated Will be removed in 1.2. Use the standard f:image viewhelper instead.
	 */
	public function render($src, $width = NULL, $height = NULL, $minWidth = NULL, $minHeight = NULL, $maxWidth = NULL, $maxHeight = NULL, $treatIdAsReference = NULL) {
		\TYPO3\CMS\Core\Utility\GeneralUtility::deprecationLog(
			'One of your DCEs uses the dce:image ViewHelper, which will be removed in version 1.2 of the DCE extension. Check frontend and backend templates and replace it with f:image.'
		);
		return parent::render($src, $width, $height, $minWidth, $minHeight, $maxWidth, $maxHeight, $treatIdAsReference);
	}
}