<?php

namespace Tests\Unit\App\Utils;

use Modules\Clean\Interfaces\ImageServiceInterface;
use Modules\Clean\Services\ImageService;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{
    private ImageService $imageService;

    public function __construct(string $name, ImageServiceInterface $imageService)
    {
        parent::__construct($name);
        $this->imageService = $imageService;
    }

    // TODO: replace test img url and path
    private string $imageUrl = 'https://kizombamagyarorszag.hu/storage/photos/shares/dikanza-semba-angola-min.jpg';
    private string $absolutePath = '/storage/photos/shares/dikanza-semba-angola-min.jpg';


    /**
     * Should return a correct abspath
     *
     * @return void
     */
    public function test_get_absolute_path_from_image_url(): void
    {
        $absolutePath = $this->imageService->getImageAbsolutePath($this->imageUrl);
        $this->assertEquals($this->absolutePath, $absolutePath);
    }


    /**
     * Image url argument should be a real url
     *
     * @return void
     */
    public function test_image_url_is_url()
    {
        $this->assertEquals(filter_var($this->imageUrl, FILTER_VALIDATE_URL), $this->imageUrl);
    }


    /**
     * The image should be inside /storage/photos/shares/ folder
     *
     * @return void
     */
    public function test_image_url_has_the_correct_storage_location(): void
    {
        $this->assertStringContainsString('/storage/photos/shares/', $this->imageUrl,
            'The image should be inside /storage/photos/shares/ folder');
    }
}
