export type Destination = {
  slug: string;
  name: string;
  region: string;
  headline: string;
  stat: string;
  story: string;
};

// Order mirrors the numbered source files in PLACES/MINI-PLANET; each slug maps to
// /hero-planets/<slug>/ (Blender turn frames) and /planets/<slug>-closeup-{1920,3840}.webp.
export const destinations: Destination[] = [
  {
    slug: "paris",
    name: "Paris",
    region: "France",
    headline: "Paris: The City That Taught the World to Linger",
    stat: "Eiffel Tower, completed 1889",
    story:
      "Mornings at a zinc counter, afternoons lost between bookstalls on the Seine, evenings that end later than planned. Paris rewards the traveler who slows down.",
  },
  {
    slug: "rome",
    name: "Rome",
    region: "Italy",
    headline: "Rome: Where Every Street Is a Chapter",
    stat: "Colosseum, inaugurated AD 80",
    story:
      "Ancient stone under your feet, espresso standing at the bar, a piazza that turns gold at dusk. Rome is less a city to see than one to walk through.",
  },
  {
    slug: "spain",
    name: "Spain",
    region: "Barcelona & beyond",
    headline: "Spain: Long Dinners, Bright Streets",
    stat: "Sagrada Família, under construction since 1882",
    story:
      "Gaudí's stone forest in Barcelona, tapas that stretch into midnight, coastlines that make you forget the calendar.",
  },
  {
    slug: "grece",
    name: "Greece",
    region: "Santorini & the Aegean",
    headline: "Greece: White Walls, Blue Domes, Endless Sea",
    stat: "Thousands of islands across the Aegean and Ionian seas",
    story:
      "Sunsets over the caldera, ferries hopping between islands, and tavernas where the fish was swimming that morning.",
  },
  {
    slug: "tokyo",
    name: "Tokyo",
    region: "Japan",
    headline: "Tokyo: Neon Calm",
    stat: "Tokyo Tower, 333 m tall",
    story:
      "Quiet shrines tucked behind glass towers, trains that run to the second, and a counter seat at a restaurant you will talk about for years.",
  },
  {
    slug: "jungle",
    name: "The Jungle",
    region: "Rainforest expeditions",
    headline: "The Jungle: Where the Canopy Breathes",
    stat: "Rainforest lodges, river trails and wildlife",
    story:
      "Wake to birdsong instead of alarms. Guided walks under a living roof, rivers by canoe, and nights that sound nothing like home.",
  },
  {
    slug: "dubai",
    name: "Dubai",
    region: "United Arab Emirates",
    headline: "Dubai: The Skyline That Keeps Rising",
    stat: "Burj Khalifa, 828 m tall",
    story:
      "Desert dunes at sunrise, souks scented with spice, and an observation deck above the clouds before dinner.",
  },
  {
    slug: "istanbul",
    name: "Istanbul",
    region: "Türkiye",
    headline: "Istanbul: One City, Two Continents",
    stat: "Spans Europe and Asia across the Bosphorus",
    story:
      "Call to prayer drifting over the water, ferries crossing between continents, and bazaars that have traded for centuries.",
  },
  {
    slug: "mountain-peak",
    name: "Patagonia",
    region: "Argentina & Chile",
    headline: "Patagonia: Granite Spires at the End of the World",
    stat: "Glaciers and granite peaks at the southern tip of South America",
    story:
      "A morning hike under jagged spires, ice fields that stretch past the horizon, and a silence that resets everything.",
  },
  {
    slug: "punta-cana",
    name: "Punta Cana",
    region: "Dominican Republic",
    headline: "Punta Cana: Turquoise on Repeat",
    stat: "Caribbean and Atlantic coastline",
    story:
      "Palm shade, warm water, and nowhere you need to be. Sometimes the best itinerary is the one with nothing on it.",
  },
  {
    slug: "thailand",
    name: "Thailand",
    region: "Bangkok & the islands",
    headline: "Thailand: Temples, Islands, Street Food",
    stat: "Golden temples and Andaman Sea islands",
    story:
      "Saffron robes at dawn, longtail boats to limestone coves, and night markets where every stall deserves a second visit.",
  },
  {
    slug: "israel",
    name: "Israel",
    region: "Jerusalem & the Holy Land",
    headline: "Israel: Where History Walks Beside You",
    stat: "Old City of Jerusalem, UNESCO World Heritage since 1981",
    story:
      "Stones worn smooth by millennia of pilgrims, Mediterranean beaches an hour away, and stories that live in every alley.",
  },
  {
    slug: "golden-circle",
    name: "Golden Circle",
    region: "Iceland",
    headline: "The Golden Circle: Fire, Ice and Falling Water",
    stat: "Þingvellir, Geysir and Gullfoss in one loop",
    story:
      "Stand between two tectonic plates, wait for a geyser to erupt, then feel the spray of a waterfall that seems to fall into the earth.",
  },
  {
    slug: "machu-picchu",
    name: "Machu Picchu",
    region: "Peru",
    headline: "Machu Picchu: The City Above the Clouds",
    stat: "About 2,430 m above sea level",
    story:
      "Morning mist lifting off Inca terraces, the Andes in every direction, and the feeling that you earned this view.",
  },
];
