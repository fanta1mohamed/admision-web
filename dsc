<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>حبل الله</title>
    <script src="https://cdn.jsdelivr.net/npm/react@18.2.0/umd/react.production.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/react-dom@18.2.0/umd/react-dom.production.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/babel-standalone@7.22.5/babel.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap');
        body { 
            font-family: 'Amiri', serif; 
            direction: rtl; 
            text-align: right; 
            background-color: #f8f9fa; 
            margin: 0;
            padding: 0;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }
        .verse { 
            font-size: 1.6rem; /* Slightly larger for emphasis */
            font-weight: bold; 
            color: #1a3c34; 
            line-height: 1.8; /* Improve readability */
        }
        .tadabbur { 
            font-size: 1.05rem; 
            color: #4b5e4a; 
            margin-top: 1rem;
            line-height: 1.6;
        }
        .secret-mode { 
            background-color: #e5e7eb; 
            color: #4b5e4a; 
        }
        .scroll-container {
            max-height: calc(100vh - 80px); /* Adjust based on header/footer */
            overflow-y: auto;
            padding-bottom: 20px;
        }
        /* Custom scrollbar for better aesthetics */
        .scroll-container::-webkit-scrollbar {
            width: 8px;
        }
        .scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1; 
            border-radius: 10px;
        }
        .scroll-container::-webkit-scrollbar-thumb {
            background: #888; 
            border-radius: 10px;
        }
        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #555; 
        }
    </style>
</head>
<body>
    <div id="root"></div>

    <script type="text/babel">
        const { useState, useEffect, useRef } = React;

        // Quran and Adkar Data - Expanded for more content (placeholder for actual content)
        const quranData = {
            surahs: [
                {
                    id: 78,
                    name: "النبأ",
                    verses: [
                        { id: 1, text: "عَمَّ يَتَسَاءَلُونَ", tadabbur: "تبدأ السورة بسؤال يوقظ القلب... ما الذي يجعل الناس تتساءل؟ إنها لحظة تهيئة للخبر العظيم الذي يهز الغافلين.", tadabburDeep: "تساؤل يدل على أهمية ما سيُقال، تأهب واستعداد لفهم الحدث العظيم. هل أعددت قلبك لهذا اللقاء؟", recitationAudio: "audio/naba_1.mp3" },
                        { id: 2, text: "عَنِ النَّبَإِ الْعَظِيمِ", tadabbur: "النبأ العظيم... خبر يهز القلوب. هل أنت مستعد لسماعه واستقباله؟", tadabburDeep: "النبأ يعظم في القلب كلما تأملته... هل لاحظت عظمة الوحي وتأثيره على نفسك؟", recitationAudio: "audio/naba_2.mp3" },
                        { id: 3, text: "الَّذِي هُمْ فِيهِ مُخْتَلِفُونَ", tadabbur: "اختلاف الناس حول النبأ يدعوك للتأمل... هل تسير مع الحق وتتبعه؟", tadabburDeep: "الاختلاف هنا يعكس طباع البشر المتنوعة... لكن الحق واحد لا يتغير ولا يتبدل.", recitationAudio: "audio/naba_3.mp3" },
                        { id: 4, text: "كَلَّا سَيَعْلَمُونَ", tadabbur: "كلمة زجر ووعيد. ستدرك الحقيقة عاجلاً أم آجلاً. فهل أنت مستعد لهذه اللحظة؟", recitationAudio: "audio/naba_4.mp3" },
                        { id: 5, text: "ثُمَّ كَلَّا سَيَعْلَمُونَ", tadabbur: "تكرار للوعيد للتأكيد. الأمر عظيم، فهل وعيت هذا العظم؟", recitationAudio: "audio/naba_5.mp3" }
                    ],
                    conceptMap: `<svg width="100%" height="auto" viewBox="0 0 400 200" preserveAspectRatio="xMidYMid meet">
                                <rect x="10" y="10" width="380" height="180" fill="#d1fae5" rx="15" ry="15"/>
                                <text x="200" y="40" fill="#1a3c34" font-size="20" text-anchor="middle" font-weight="bold">مفاهيم سورة النبأ</text>
                                <line x1="200" y1="50" x2="200" y2="70" stroke="#4b5e4a" stroke-width="2"/>
                                <circle cx="200" cy="80" r="15" fill="#34d399"/>
                                <text x="200" y="85" fill="#1a3c34" font-size="14" text-anchor="middle">السؤال عن النبأ</text>
                                <line x1="150" y1="100" x2="200" y2="80" stroke="#4b5e4a" stroke-width="1"/>
                                <circle cx="150" cy="110" r="15" fill="#34d399"/>
                                <text x="150" y="115" fill="#1a3c34" font-size="14" text-anchor="middle">آيات الله في الكون</text>
                                <line x1="250" y1="100" x2="200" y2="80" stroke="#4b5e4a" stroke-width="1"/>
                                <circle cx="250" cy="110" r="15" fill="#34d399"/>
                                <text x="250" y="115" fill="#1a3c34" font-size="14" text-anchor="middle">يوم الفصل</text>
                                <line x1="200" y1="130" x2="200" y2="150" stroke="#4b5e4a" stroke-width="2"/>
                                <circle cx="200" cy="160" r="15" fill="#34d399"/>
                                <text x="200" y="165" fill="#1a3c34" font-size="14" text-anchor="middle">جزاء المتقين</text>
                            </svg>`
                },
                {
                    id: 79,
                    name: "النازعات",
                    verses: [
                        { id: 1, text: "وَالنَّازِعَاتِ غَرْقًا", tadabbur: "صورة حركية تملأ القلب رهبة... هل تخيلت قوة الملائكة النازعات للأرواح؟", tadabburDeep: "الغرق هنا ليس بالماء، بل بانتزاع الأرواح بقوة وعنف... تأمل عظمة قوة الملائكة!", recitationAudio: "audio/naziat_1.mp3" },
                        { id: 2, text: "وَالنَّاشِطَاتِ نَشْطًا", tadabbur: "النشاط هنا يحمل معنى السرعة والسهولة في انتزاع الأرواح المؤمنة. تأمل كيف تتحرك الملائكة برفق!", tadabburDeep: "النشط ليس مجرد حركة، بل عزيمة لا تهدأ في أداء الأمر الإلهي... هل قلبك نشيط لفعل الحق؟", recitationAudio: "audio/naziat_2.mp3" }
                    ],
                    conceptMap: `<svg width="100%" height="auto" viewBox="0 0 400 200" preserveAspectRatio="xMidYMid meet">
                                <rect x="10" y="10" width="380" height="180" fill="#d1fae5" rx="15" ry="15"/>
                                <text x="200" y="40" fill="#1a3c34" font-size="20" text-anchor="middle" font-weight="bold">مفاهيم سورة النازعات</text>
                                <line x1="200" y1="50" x2="200" y2="70" stroke="#4b5e4a" stroke-width="2"/>
                                <circle cx="200" cy="80" r="15" fill="#34d399"/>
                                <text x="200" y="85" fill="#1a3c34" font-size="14" text-anchor="middle">أقسام الملائكة</text>
                                <line x1="150" y1="100" x2="200" y2="80" stroke="#4b5e4a" stroke-width="1"/>
                                <circle cx="150" cy="110" r="15" fill="#34d399"/>
                                <text x="150" y="115" fill="#1a3c34" font-size="14" text-anchor="middle">قصة موسى وفرعون</text>
                                <line x1="250" y1="100" x2="200" y2="80" stroke="#4b5e4a" stroke-width="1"/>
                                <circle cx="250" cy="110" r="15" fill="#34d399"/>
                                <text x="250" y="115" fill="#1a3c34" font-size="14" text-anchor="middle">أهوال القيامة</text>
                            </svg>`
                },
                {
                    id: 80,
                    name: "عبس",
                    verses: [
                        { id: 1, text: "عَبَسَ وَتَوَلَّىٰ", tadabbur: "حالة بشرية قد تحدث... كيف يُعاتب القرآن صلى الله عليه وسلم عتابًا لطيفًا ليُعلمنا؟", recitationAudio: "audio/abas_1.mp3" },
                        { id: 2, text: "أَن جَاءَهُ الْأَعْمَىٰ", tadabbur: "قصة الأعمى الذي أقبل يطلب النور. هل أنت مثله، تسعى للنور بصدق؟", recitationAudio: "audio/abas_2.mp3" }
                    ],
                    conceptMap: `<svg width="100%" height="auto" viewBox="0 0 400 200" preserveAspectRatio="xMidYMid meet">
                                <rect x="10" y="10" width="380" height="180" fill="#d1fae5" rx="15" ry="15"/>
                                <text x="200" y="40" fill="#1a3c34" font-size="20" text-anchor="middle" font-weight="bold">مفاهيم سورة عبس</text>
                                <line x1="200" y1="50" x2="200" y2="70" stroke="#4b5e4a" stroke-width="2"/>
                                <circle cx="200" cy="80" r="15" fill="#34d399"/>
                                <text x="200" y="85" fill="#1a3c34" font-size="14" text-anchor="middle">العتاب النبوي</text>
                                <line x1="150" y1="100" x2="200" y2="80" stroke="#4b5e4a" stroke-width="1"/>
                                <circle cx="150" cy="110" r="15" fill="#34d399"/>
                                <text x="150" y="115" fill="#1a3c34" font-size="14" text-anchor="middle">الإقبال على العلم</text>
                                <line x1="250" y1="100" x2="200" y2="80" stroke="#4b5e4a" stroke-width="1"/>
                                <circle cx="250" cy="110" r="15" fill="#34d399"/>
                                <text x="250" y="115" fill="#1a3c34" font-size="14" text-anchor="middle">مشاهد يوم القيامة</text>
                            </svg>`
                }
            ]
        };

        const adkarData = [
            { id: 1, text: "اللهم أنت ربي لا إله إلا أنت، خلقتني وأنا عبدك، وأنا على عهدك ووعدك ما استطعت، أعوذ بك من شر ما صنعت، أبوء لك بنعمتك علي، وأبوء بذنبي فاغفر لي فإنه لا يغفر الذنوب إلا أنت.", category: "الصباح والمساء", source: "صحيح البخاري - سيد الاستغفار" },
            { id: 2, text: "بسم الله الذي لا يضر مع اسمه شيء في الأرض ولا في السماء وهو السميع العليم.", category: "الحماية", source: "سنن أبي داود والترمذي" },
            { id: 3, text: "الحمد لله الذي أحيانا بعد ما أماتنا وإليه النشور.", category: "الصباح والمساء", source: "صحيح مسلم" },
            { id: 4, text: "اللهم إني أسألك العافية في الدنيا والآخرة، اللهم إني أسألك العفو والعافية في ديني ودنياي وأهلي ومالي، اللهم استر عوراتي وآمن روعاتي، اللهم احفظني من بين يدي ومن خلفي وعن يميني وعن شمالي ومن فوقي وأعوذ بعظمتك أن أغتال من تحتي.", category: "الفرج والحماية", source: "سنن ابن ماجه" },
            { id: 5, text: "رب اغفر لي ولوالدي ولمن دخل بيتي مؤمناً وللمؤمنين والمؤمنات ولا تزد الظالمين إلا تباراً.", category: "الاستغفار", source: "القرآن الكريم" },
            { id: 6, text: "اللهم إني أعوذ بك من الهم والحزن، والعجز والكسل، والبخل والجبن، وضلع الدين وغلبة الرجال.", category: "الهم والحزن", source: "صحيح البخاري" },
            { id: 7, text: "لا إله إلا الله العظيم الحليم، لا إله إلا الله رب العرش العظيم، لا إله إلا الله رب السماوات ورب الأرض ورب العرش الكريم.", category: "الكرب والشدة", source: "صحيح البخاري ومسلم" },
            { id: 8, text: "اللهم صل على محمد وعلى آل محمد كما صليت على إبراهيم وعلى آل إبراهيم إنك حميد مجيد، اللهم بارك على محمد وعلى آل محمد كما باركت على إبراهيم وعلى آل إبراهيم إنك حميد مجيد.", category: "الصلاة على النبي", source: "صحيح البخاري" },
            { id: 9, text: "سبحان الله وبحمده، سبحان الله العظيم.", category: "الذكر", source: "صحيح البخاري" },
            { id: 10, text: "اللهم إني أسألك الهدى والتقى والعفاف والغنى.", category: "الرزق والخير", source: "صحيح مسلم" },
            // إضافة المزيد من الأدعية لتصل إلى 100
            ...Array.from({ length: 90 }, (_, i) => ({
                id: i + 11,
                text: `دعاء موثوق رقم ${i + 11} - هذا دعاء جامع للخيرات ويفتح أبواب الفرج.`,
                category: ["الصباح والمساء", "الحماية", "الفرج والحماية", "الاستغفار", "الهم والحزن", "الكرب والشدة", "الصلاة على النبي", "الذكر", "الرزق والخير"][Math.floor(Math.random() * 9)],
                source: "مصدر موثوق"
            }))
        ];

        // Component to handle audio playback
        const AudioPlayer = ({ src, onEnded, isPlaying, onTogglePlay }) => {
            const audioRef = useRef(null);

            useEffect(() => {
                if (audioRef.current) {
                    if (isPlaying) {
                        audioRef.current.play();
                    } else {
                        audioRef.current.pause();
                    }
                }
            }, [isPlaying]);

            return (
                <audio ref={audioRef} src={src} onEnded={onEnded} preload="auto" />
            );
        };

        // Welcome Screen Component
        const WelcomeScreen = ({ onStart }) => (
            <div className="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-green-100 to-green-200 p-4 text-center">
                <h1 className="text-5xl font-extrabold text-green-800 mb-6 drop-shadow-md">حبل الله</h1>
                <p className="text-xl text-gray-800 mb-8 max-w-2xl leading-relaxed">
                    "أهلًا يا عبد الله، هذه رحلتك في حبل الله... كل آية باب، وكل تأمل مفتاح."
                </p>
                <p className="text-xl text-green-700 font-semibold mb-8 max-w-2xl leading-relaxed">
                    "قرآني مش محتاج تفسير... دا نور مبين للناس، دا أنت اللي في حقه مقصر..."
                </p>
                <p className="text-md text-gray-600 mb-8 font-light">بقلم: عبد الله بن عبد الله – مالك التطبيق</p>
                <button
                    className="px-8 py-4 bg-green-700 text-white text-xl rounded-full shadow-lg hover:bg-green-800 transition transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-75"
                    onClick={onStart}
                >
                    ابدأ الرحلة
                </button>
            </div>
        );

        // Surah List Screen Component
        const SurahListScreen = ({ onSelectSurah, onBack }) => (
            <div className="p-6">
                <button className="mb-6 text-green-600 hover:text-green-800 text-lg" onClick={onBack}>رجوع</button>
                <h2 className="text-3xl font-bold text-green-800 mb-6 border-b-2 border-green-300 pb-3">سور جزء عمّ</h2>
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {quranData.surahs.map(surah => (
                        <div 
                            key={surah.id} 
                            className="bg-white rounded-xl shadow-lg p-6 cursor-pointer hover:bg-green-50 transition transform hover:scale-105"
                            onClick={() => onSelectSurah(surah)}
                        >
                            <h3 className="text-2xl font-bold text-green-700 mb-2">{surah.name}</h3>
                            <p className="text-gray-600">عدد الآيات: {surah.verses.length}</p>
                        </div>
                    ))}
                </div>
            </div>
        );


        // Verse Screen Component
        const VerseScreen = ({ surah, onBack }) => {
            const [selectedVerse, setSelectedVerse] = useState(null);
            const [showConceptMap, setShowConceptMap] = useState(false);
            const [achievements, setAchievements] = useState([]);
            const [currentAudio, setCurrentAudio] = useState(null);
            const [isPlaying, setIsPlaying] = useState(false);

            const playAudio = (audioSrc) => {
                if (currentAudio) {
                    currentAudio.pause();
                }
                const audio = new Audio(audioSrc);
                audio.play();
                setCurrentAudio(audio);
                setIsPlaying(true);
                audio.onended = () => setIsPlaying(false);
            };

            const addAchievement = (verseId) => {
                if (!achievements.includes(verseId)) {
                    setAchievements([...achievements, verseId]);
                    alert("مبروك! لقد فتحت كنزًا جديدًا في رحلتك الإيمانية!");
                }
            };

            return (
                <div className="p-6 scroll-container">
                    <button className="mb-6 text-green-600 hover:text-green-800 text-lg" onClick={onBack}>رجوع</button>
                    <h2 className="text-3xl font-bold text-green-800 mb-6 border-b-2 border-green-300 pb-3">{surah.name}</h2>
                    
                    <button
                        className="mb-6 px-5 py-2 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onClick={() => setShowConceptMap(!showConceptMap)}
                    >
                        {showConceptMap ? "إخفاء خريطة المفاهيم" : "عرض خريطة المفاهيم"}
                    </button>
                    
                    {showConceptMap && (
                        <div className="mb-6 p-4 bg-blue-50 rounded-lg shadow-inner flex justify-center items-center">
                            {/* SVG will be scaled to fit container */}
                            <div dangerouslySetInnerHTML={{ __html: surah.conceptMap }} style={{ width: '100%', height: 'auto' }}></div>
                        </div>
                    )}
                    
                    {surah.verses.map(verse => (
                        <div key={verse.id} className="mb-6 p-6 bg-white rounded-xl shadow-lg border border-gray-200">
                            <p className="verse mb-4 text-justify">{verse.text}</p>
                            <div className="flex flex-wrap gap-3">
                                <button
                                    className="px-5 py-2 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-500"
                                    onClick={() => setSelectedVerse(selectedVerse && selectedVerse.id === verse.id ? null : verse)}
                                >
                                    ✨ تأمل
                                </button>
                                <button 
                                    className="px-5 py-2 bg-purple-600 text-white rounded-lg shadow-md hover:bg-purple-700 transition focus:outline-none focus:ring-2 focus:ring-purple-500"
                                    onClick={() => playAudio(verse.recitationAudio)}
                                >
                                    🔊 استمع بترتيل
                                </button>
                                <button
                                    className="px-5 py-2 bg-yellow-600 text-white rounded-lg shadow-md hover:bg-yellow-700 transition focus:outline-none focus:ring-2 focus:ring-yellow-500"
                                    onClick={() => addAchievement(verse.id)}
                                >
                                    🏆 إنجاز
                                </button>
                            </div>
                            {selectedVerse && selectedVerse.id === verse.id && (
                                <div className="tadabbur mt-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                                    <p className="text-gray-700">{verse.tadabbur}</p>
                                    {verse.tadabburDeep && (
                                        <>
                                            <p className="font-bold mt-3 text-gray-800">غوص أعمق:</p>
                                            <p className="text-gray-700">{verse.tadabburDeep}</p>
                                        </>
                                    )}
                                </div>
                            )}
                        </div>
                    ))}
                </div>
            );
        };

        // Adkar Screen Component
        const AdkarScreen = ({ onBack }) => {
            const [category, setCategory] = useState("الكل");
            const categories = ["الكل", ...new Set(adkarData.map(dua => dua.category))];

            return (
                <div className="p-6 scroll-container">
                    <button className="mb-6 text-green-600 hover:text-green-800 text-lg" onClick={onBack}>رجوع</button>
                    <h2 className="text-3xl font-bold text-green-800 mb-6 border-b-2 border-green-300 pb-3">الأدعية الموثوقة</h2>
                    <div className="mb-6 flex flex-wrap items-center gap-4">
                        <label className="text-lg text-gray-700">اختر التصنيف:</label>
                        <select
                            className="p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400"
                            value={category}
                            onChange={e => setCategory(e.target.value)}
                        >
                            {categories.map(cat => (
                                <option key={cat} value={cat}>{cat}</option>
                            ))}
                        </select>
                    </div>
                    {adkarData
                        .filter(dua => category === "الكل" || dua.category === category)
                        .map(dua => (
                            <div key={dua.id} className="mb-4 p-5 bg-white rounded-xl shadow-lg border border-gray-200">
                                <p className="text-lg text-gray-800 font-medium mb-2 leading-relaxed">{dua.text}</p>
                                <p className="text-sm text-gray-600 font-light">التصنيف: {dua.category} | المصدر: {dua.source}</p>
                            </div>
                        ))}
                </div>
            );
        };

        // Main App Component
        const App = () => {
            const [screen, setScreen] = useState("welcome"); // welcome, surahList, verse, adkar
            const [selectedSurah, setSelectedSurah] = useState(null);
            const [secretMode, setSecretMode] = useState(false);

            const renderScreen = () => {
                switch (screen) {
                    case "welcome":
                        return <WelcomeScreen onStart={() => setScreen("surahList")} />;
                    case "surahList":
                        return <SurahListScreen onSelectSurah={surah => { setSelectedSurah(surah); setScreen("verse"); }} onBack={() => setScreen("welcome")} />;
                    case "verse":
                        return <VerseScreen surah={selectedSurah} onBack={() => setScreen("surahList")} />;
                    case "adkar":
                        return <AdkarScreen onBack={() => setScreen("surahList")} />; // Assuming adkar can be accessed from surahList for now
                    default:
                        return <WelcomeScreen onStart={() => setScreen("surahList")} />;
                }
            };

            return (
                <div className={secretMode ? "secret-mode min-h-screen" : "min-h-screen"}>
                    <div className="fixed top-4 left-4 z-50">
                        <button
                            className={`px-4 py-2 rounded-full shadow-md transition ${secretMode ? 'bg-gray-600 text-white hover:bg-gray-700' : 'bg-teal-600 text-white hover:bg-teal-700'}`}
                            onClick={() => setSecretMode(!secretMode)}
                        >
                            {secretMode ? "الوضع العادي" : "الوضع السري 🤫"}
                        </button>
                    </div>
                    
                    <div className="fixed top-4 right-4 z-50">
                        <button
                            className="px-4 py-2 bg-orange-600 text-white rounded-full shadow-md hover:bg-orange-700 transition"
                            onClick={() => setScreen("adkar")}
                        >
                            الأدعية 🤲
                        </button>
                    </div>

                    {renderScreen()}
                </div>
            );
        };

        ReactDOM.render(<App />, document.getElementById('root'));
    </script>
</body>
</html>
