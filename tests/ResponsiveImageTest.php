<?php

namespace Aammui\LaravelMedia\Tests;

use Aammui\LaravelMedia\Enum\Extension;
use Aammui\LaravelMedia\Enum\Responsive;
use Aammui\LaravelMedia\Models\Media;
use Aammui\LaravelMedia\Service\ImageResponsiveService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;

/**
 * Class ResponsiveImageTest
 * @package Aammui\LaravelMedia\Tests
 */
class ResponsiveImageTest extends TestCase
{
    #[Test]
    public function it_stores_images_resized_to_configured_sizes_keeping_aspect_ratio()
    {
        Storage::fake('public');

        $media = TestModel::create()->addMedia(UploadedFile::fake()->image('photo.png', 1000, 800));

        $expected = [
            Responsive::SM => [50, 40],
            Responsive::MD => [150, 120],
            Responsive::LG => [600, 480],
        ];

        foreach ($expected as $size => [$width, $height]) {
            $path = ltrim(Str::after($media->link($size), $media->base_url), '/');
            $contents = Storage::disk('public')->get($path);
            [$actualWidth, $actualHeight, $type] = getimagesizefromstring($contents);

            $this->assertSame([$width, $height], [$actualWidth, $actualHeight], "Size {$size}");
            $this->assertSame(IMAGETYPE_PNG, $type, "Size {$size} keeps the original format");
        }
    }

    #[Test]
    public function responsive_images_url_can_access()
    {
        $image = UploadedFile::fake()->image('avatar.jpg');
        $model = TestModel::create();

        // Add Media to $model
        $model->addMedia($image);

        $image = $model->setMediaType(Extension::IMAGE)->getMedia()->first();
        $this->assertNotNull($image->link(Responsive::SM));
        $this->assertNotNull($image->link(Responsive::MD));
        $this->assertNotNull($image->link(Responsive::LG));
    }

    #[Test]
    public function it_generates_valid_url_for_all_the_responsive_images()
    {
        $image = UploadedFile::fake()->image('avatar.jpg');
        $model = TestModel::create();
        $model->addMedia($image);
        $model->addMedia($image);
        $this->assertInstanceOf(Collection::class, $model->getMedia());
        $this->assertInstanceOf(Media::class, $model->getMedia()->first());
        $this->assertEquals(2, $model->getMedia()->count());

        // Assert built url is valid
        foreach (Responsive::getConfig() as $key => $responsive) {
            $this->assertMatchesRegularExpression(
                "/http:\/\/localhost\/{$key}\/.*/", $model->getMedia()
                ->first()
                ->link($key)
            );
        }
    }
}
