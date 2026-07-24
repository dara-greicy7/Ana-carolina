export type GalleryDestination = {
  title: string;
  slug: string;
  summary: string;
  images: string[];
};

export const galleryDestinations: GalleryDestination[] = [
  {
    title: "Sao Paulo",
    slug: "sao-paulo",
    summary:
      "Urban movement, skyline contrast, and a sharp editorial rhythm built from the full Sao Paulo set.",
    images: [
      "/images/legacy/gallery/sao-paulo/01.jpg",
      "/images/legacy/gallery/sao-paulo/02.jpg",
      "/images/legacy/gallery/sao-paulo/03.jpg",
      "/images/legacy/gallery/sao-paulo/04.jpg",
      "/images/legacy/gallery/sao-paulo/05.jpg",
      "/images/legacy/gallery/sao-paulo/06.jpg",
      "/images/legacy/gallery/sao-paulo/07.jpg",
      "/images/legacy/gallery/sao-paulo/08.jpg",
    ],
  },
  {
    title: "Rio de Janeiro",
    slug: "rio-de-janeiro",
    summary:
      "Coastal texture, layered light, and a larger travel story built from the complete Rio archive.",
    images: [
      "/images/legacy/gallery/rio-de-janeiro/01.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141113_063404.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141113_063505.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141114_121439.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_084804.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_085050.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_085317.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_115734.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_120006.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_120509.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_121824.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_124304.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_154906.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_155302.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_160146.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141116_163239_RichtoneHDR.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141117_214923.jpg",
      "/images/legacy/gallery/rio-de-janeiro/20141118_154152.jpg",
      "/images/legacy/gallery/rio-de-janeiro/IMG_20150331_000408.jpg",
      "/images/legacy/gallery/rio-de-janeiro/IMG_209150331_000408.jpg",
    ],
  },
  {
    title: "Republica Dominicana",
    slug: "republica-dominicana",
    summary:
      "A warm island sequence with documentary energy and the full Republica Dominicana image set.",
    images: [
      "/images/legacy/gallery/republica-dominicana/01.jpg",
      "/images/legacy/gallery/republica-dominicana/20141209_103015.jpg",
      "/images/legacy/gallery/republica-dominicana/20141209_104410.jpg",
      "/images/legacy/gallery/republica-dominicana/20141209_143150.jpg",
      "/images/legacy/gallery/republica-dominicana/20141212_101509.jpg",
      "/images/legacy/gallery/republica-dominicana/20141212_102829.jpg",
      "/images/legacy/gallery/republica-dominicana/20141212_111139.jpg",
      "/images/legacy/gallery/republica-dominicana/20141212_112506.jpg",
      "/images/legacy/gallery/republica-dominicana/20141212_113617.jpg",
      "/images/legacy/gallery/republica-dominicana/20141212_114255.jpg",
      "/images/legacy/gallery/republica-dominicana/20141212_180034.jpg",
      "/images/legacy/gallery/republica-dominicana/20150215_172924.jpg",
      "/images/legacy/gallery/republica-dominicana/IMG-20141226-WA0035.jpg",
      "/images/legacy/gallery/republica-dominicana/IMG-20150117-WA0010.jpg",
      "/images/legacy/gallery/republica-dominicana/IMG-20150406-WA0000.jpg",
      "/images/legacy/gallery/republica-dominicana/IMG_20141213_085203.jpg",
      "/images/legacy/gallery/republica-dominicana/IMG_20141225_213415.jpg",
    ],
  },
];

export function getGalleryDestination(slug: string | undefined) {
  return galleryDestinations.find((destination) => destination.slug === slug) ?? galleryDestinations[0];
}
