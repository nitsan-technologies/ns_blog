# NS Blog Upgrade Notes

This extension replaces the old `blog` dependency for T3Karma with a reduced feature set:

- post list
- latest posts
- category listing/filter
- author listing/filter

## Migration steps

1. Enable `ns_blog`.
2. Run database compare and apply schema changes.
3. Run install tool upgrade wizards:
   - `Migrate blog plugin signatures to ns_blog`
   - `Validate ns_blog migration integrity`
4. Clear all caches.
5. Validate on staging:
   - list/latest/category/author pages
   - existing blog content elements
   - route links for category and author pages

## Scope notes

- Tags, comments, archive, and notification features are intentionally out of scope.
- Existing data structures are kept compatibility-first (posts on `pages`, authors in `tx_blog_domain_model_author`).
