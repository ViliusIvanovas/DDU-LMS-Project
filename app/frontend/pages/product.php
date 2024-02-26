<?php
$product_id = $_GET['product_id'];

$product = Product::getProductById($product_id);
?>

<div class="split">
  <div class="row">
    <div class="col-sm-4">
    </div>
    <div class="productDesc">
      <h2Black>
        <?php echo $product->name ?>
      </h2Black>
      <p>
        <?php echo $product->description ?>
      </p>
      <p>
        <?php $price = Product::getCurrentPrice($product_id) ?>
        <?php echo "$" . $price; ?>

        <?php $priceO = Product::getOriginalPrice($product_id) ?>
        <?php echo "$" . $priceO; ?>

        <?php $discount = Product::getDiscount($product_id) ?>
        <?php echo $discount . "%"; ?>
      </p>
      <select id="productVariations">
        <option value="">Select a variation</option>
        <?php
        $variations = Product::getProductVariationsById($product_id);

        usort($variations, function ($a, $b) {
          return $a->name <=> $b->name;
        });

        foreach ($variations as $variation) {
        ?>
          <option value="<?php echo $variation->variation_id ?>"><?php echo $variation->name ?></option>
        <?php
        }
        ?>
      </select>
      <p id="stock"></p>
      <script>
        document.getElementById('productVariations').addEventListener('change', function() {
          var addToCartButton = document.querySelector('input[name="add_to_cart"]');
          if (this.value === "") {
            document.getElementById('stock').textContent = "";
            return;
          }

          document.getElementById('selectedVariation').value = this.value;

          fetch('get-stock.php?variation_id=' + this.value)
            .then(response => response.text())
            .then(data => {
              var stockText = "Stock: " + data;
              document.getElementById('stock').textContent = stockText;

              // If the stock is 0, disable the "Add to Cart" button. Otherwise, enable it.
              document.querySelector('input[name="add_to_cart"]').disabled = (data == 0);
            })
            .catch(error => console.error('Error:', error));
        });
      </script>

      <form method="post" action="cart.php">
        <input type="hidden" name="product_id" value="<?php echo $product->product_id ?>">
        <input type="hidden" id="selectedVariation" name="variation_id">
        <input type="hidden" name="quantity" value="1">
        <input type="submit" name="add_to_cart" value="Add to Cart">
      </form>
    </div>
  </div>

  <?php

  $images = Product::getImagesByProductId($product_id);
  foreach ($images->results() as $image) {
  ?>
    <div class="imageAlignment">
      <?php
      echo "<img src='" . $image->image_location . "' width='100%' height='100%'>" . "<br>";
      ?>
    </div>
  <?php
  }

  ?>

</div>

<div class="row reviews">
  <div class="col-sm-4">
    <h2 class="reviewsHeader">Reviews</h2>

    <?php if ($user->isLoggedIn()) { ?>
      <a href="add-review.php?product_id=<?php echo $product_id ?>" class="btn btn-primary">Post a review</a> <br>
    <?php } ?>

    <?php
    $reviews = Review::getReviewsByProductId($product_id);
    if ($reviews !== null && $reviews->count() > 0) {
      foreach ($reviews->results() as $review) {
    ?>
        <div class="review">
          <p>
            <?php echo $review->rating ?>/5
          </p>
          <p>
            <?php echo $review->text ?>
          </p>
          <p>
            <?php echo $review->time ?>
          </p>
          <p>
            <?php
            $comment_user = User::getUserById($review->user_id);
            echo $comment_user->username ?>
          </p>
          <?php

          if ($user->isLoggedIn()) {
            if ($user->data()->user_id == $review->user_id) {
          ?>
              <a href="manage-review.php?review_id=<?php echo $review->review_id ?>" class="btn btn-primary">Manage</a>
          <?php
            }
          }

          ?>
        </div>
    <?php
      }
    } else {
      echo "<div class='noReviews'><p>No reviews for this product yet.</p></div>";
    }

    ?>
  </div>
</div>